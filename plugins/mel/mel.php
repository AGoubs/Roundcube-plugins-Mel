<?php
/**
 * Plugin Mél
 *
 * plugin mel pour roundcube
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License version 2
 * as published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
 */
// Chargement de la librairie ORM
@include_once 'includes/libm2.php';
require_once 'lib/drivers/driver_mel.php';

class mel extends rcube_plugin {

  static $VERSION = "Mél";

  /**
   *
   * @var string
   */
  public $task = '.*';
  /**
   *
   * @var rcmail
   */
  private $rc;
  /**
   * Tableau listant les identitées
   *
   * @var array
   */
  private $identities;
  /**
   * Stocke le _account passé en get
   *
   * @var string
   */
  private $get_account;
  /**
   * Identifiant de la bal
   *
   * @var string
   */
  private $user_bal;
  /**
   * Username complet bal@host
   *
   * @var string
   */
  private $user_name;
  /**
   * Host de l'utilisateur
   *
   * @var string
   */
  private $user_host;
  /**
   * Objet de partage, en .
   * -. si balp
   *
   * @var string
   */
  private $user_objet_share;

  /**
   * Clé pour la mise en cache
   *
   * @var string
   */
  const CACHE_KEY = "cache_m2";

  /**
   * Initialisation du plugin
   *
   * @see rcube_plugin::init()
   */
  function init() {
    $this->rc = rcmail::get_instance();
    // Mise à jour de la version
    include_once __DIR__ . '/../../version.php';
    self::$VERSION .= " " . Version::VERSION;
    // Définition des hooks
    $this->add_hook('login_after',          array($this, 'login_after'));
    $this->add_hook('user_create',          array($this, 'user_create'));
    $this->add_hook('m2_get_account',       array($this, 'm2_get_account'));
    $this->add_hook('smtp_connect',         array($this, 'smtp_connect'));
    $this->add_hook('preferences_list',     array($this, 'prefs_list'));
    $this->add_hook('preferences_save',     array($this, 'prefs_save'));
    $this->add_hook('identity_form',        array($this, 'identity_form'));
    $this->add_hook('identities_list',      array($this, 'identities_list'));
    // register message hook
    $this->add_hook('message_headers_output', array($this, 'mail_headers'));

    // Template
    $this->add_hook('template_object_loginform',  array($this, 'login_form'));
    $this->add_hook('template_object_version',    array($this, 'version'));

    // Command
    $this->register_action('plugin.set_current_page',       array($this, 'set_current_page'));
    $this->register_action('plugin.get_mbox_unread_count',  array($this, 'get_unread_count'));

    // Chargement de l'account passé en Get
    $this->get_account = self::get_account();
    // Chargement de l'ui
    $this->init_ui();

    // Configurer les LOG de la librairie Mél
    $debug_log = function ($message) {
      $message = "[LibM2] $message";
      if (mel_logs::is(mel_logs::DEBUG))
        mel_logs::get_instance()->log(mel_logs::DEBUG, $message);
    };
    $info_log = function ($message) {
      $message = "[LibM2] $message";
      if (mel_logs::is(mel_logs::INFO))
        mel_logs::get_instance()->log(mel_logs::INFO, $message);
    };
    $error_log = function ($message) {
      $message = "[LibM2] $message";
      if (mel_logs::is(mel_logs::ERROR))
        mel_logs::get_instance()->log(mel_logs::ERROR, $message);
    };
    LibMelanie\Log\M2Log::InitDebugLog($debug_log);
    LibMelanie\Log\M2Log::InitInfoLog($info_log);
    LibMelanie\Log\M2Log::InitErrorLog($error_log);
  }

  /**
   * Initializes plugin's UI (localization, js script)
   */
  private function init_ui() {
    if ($this->ui_initialized) {
      return;
    }
    // load localization
    $this->add_texts('localization/', true);
    $this->include_script('mel.js');
    $this->include_stylesheet($this->local_skin_path() . '/mel.css');

    // Charge la configuration
    $this->load_config();

    // MANTIS 0006003: Problème pour convertir un mail en événement dans une ouverture par double clic
    if ($this->rc->task == 'mail' && $this->rc->action == 'show') {
      $this->rc->output->include_script('treelist.js');
    }

    // Définition du host
    $http_host = $this->rc->config->get('http_host');
    if (isset($http_host)) {
      $_SERVER['HTTP_HOST'] = $http_host;
    }

    // Default task
    $this->rc->output->set_env('default_task', $this->rc->config->get('default_task', 'mail'));

    // Use infinite scroll ?
    $this->rc->output->set_env('use_infinite_scroll', $this->rc->config->get('use_infinite_scroll', true));
    
    if (!$this->rc->config->get('hide_keep_login_button', false)) {
        // Keep login
        $this->rc->output->set_env('keep_login', isset($_SESSION['_keeplogin']) ? $_SESSION['_keeplogin'] : false);
    }

    // ajouter les boites partagées
    if ($this->api->output->type == 'html') {
      if ($this->rc->task == 'calendar') {
        // Link to Settings/Folders
        $content = html::tag('li', array(
                'class' => 'separator_above'
        ), $this->api->output->button(array(
                'label' => 'mel.menumanageresources_calendars',
                'type' => 'link',
                'classact' => 'active',
                'command' => 'plugin.mel_resources_agendas',
                'task' => 'settings'
        )));
        $this->api->add_content($content, 'calendaroptionsmenu');
      }
      elseif ($this->rc->task == 'tasks') {
        // Link to Settings/Folders
        $content = html::tag('li', array(
                'class' => 'separator_above'
        ), $this->api->output->button(array(
                'label' => 'mel.menumanageresources_taskslists',
                'type' => 'link',
                'classact' => 'active',
                'command' => 'plugin.mel_resources_tasks',
                'task' => 'settings'
        )));
        $this->api->add_content($content, 'tasklistoptionsmenu');
      }
      elseif ($this->rc->task == 'login' || $this->rc->task == 'logout') {
        $this->api->add_content(html::div(null, $this->gettext('login_footer')) . html::br() . html::div(null, $this->gettext('login from') . ucfirst($_SERVER["HTTP_X_MINEQPROVENANCE"])), 'loginfooter');
      }
      // Gestion du mot de passe trop ancien
      if ($this->rc->task == 'mail' 
          && !$this->rc->output->get_env('ismobile') 
          && driver_mel::get_instance()->isPasswordNeedsToChange($passwordchange_title = '')) {
        $this->rc->output->set_env('passwordchange_title', $passwordchange_title);
        $this->rc->output->set_env('plugin.show_password_change', true);
      }
    }
    $this->ui_initialized = true;
  }
  
  /**
   * Force un account différent si besoin
   * @param string $_account
   */
  public function set_account($_account) {
    $this->get_account = $_account;
  }

  /**
   * Permet de récupérer l'account courant
   */
  public static function get_account() {
    if (isset($_POST['_account']) && !empty($_POST['_account'])) {
      $_account = trim(rcube_utils::get_input_value('_account', rcube_utils::INPUT_POST));
    }
    else {
      $_account = trim(rcube_utils::get_input_value('_account', rcube_utils::INPUT_GET));
    }
    return $_account;
  }

  /**
   * **** COMMANDS *****
   */
  /**
   * RAZ de la page courante
   */
  public function set_current_page() {
    $_SESSION['page'] = 1;
    $result = array(
            'action' => 'plugin.set_current_page'
    );
    echo json_encode($result);
    exit();
  }

  /**
   * Rafraichissement de la liste des dossiers dans la page compose
   */
  public function refresh_store_target_selection() {
    $unlock = rcube_utils::get_input_value('_unlock', rcube_utils::INPUT_GET);

    $attrib = array(
            'name'      => '_store_target',
            'maxlength' => '30',
            'style'     => 'max-width:12em',
            'tabindex'  => '4',
    );
    $select = $this->rc->folder_selector(array_merge($attrib, array(
            'noselection'   => '- ' . $this->rc->gettext('dontsave') . ' -',
            'folder_filter' => 'mail',
            'folder_rights' => 'w',
    )));

    $result = array(
            'action' => 'plugin.refresh_store_target_selection',
            'select_html' => $select->show($this->rc->config->get('sent_mbox'), $attrib),
            'unlock' => $unlock,
    );
    echo json_encode($result);
    exit;
  }

  /**
   * ****** Version *****
   */
  /**
   * Supprimer la liste des serveurs
   */
  public function version($args) {
    $args['content'] = self::$VERSION;
    return $args;
  }

  /**
   * *** HOOKS *****
   */
  /**
   * Sets defaults for new user.
   */
  public function user_create($args) {
    if (mel_logs::is(mel_logs::DEBUG))
      mel_logs::get_instance()->log(mel_logs::DEBUG, "mel::user_create()");

    $user = driver_mel::gi()->getUser($args['user']);
    // Récupération du hostname
    $hostname = driver_mel::get_instance()->getRoutage($user);
    if (isset($hostname)) {
      $args['host'] = $hostname;
    }

    // Default user name
    $args['user_name'] = $user->fullname;
    $args['user_email'] = $user->email_send;
    if (mel_logs::is(mel_logs::INFO))
      mel_logs::get_instance()->log(mel_logs::INFO, "[user_create] Création de l'utilisateur '" . $args['user_name'] . "@" . $args['host'] . "' dans la base de données Roundcube");

    // Test du calendrier
    $calendar = $user->getDefaultCalendar();
    if (!isset($calendar)) {
      $user->createDefaultCalendar($this->rc->config->get('default_calendar_name', null));
    }
    // Test du carnet d'adresses
    $addressbook = $user->getDefaultAddressbook();
    if (!isset($addressbook)) {
      $user->createDefaultAddressbook($this->rc->config->get('default_addressbook_name', null));
    }
    // Test de la liste de tâches
    $taskslist = $user->getDefaultTaskslist();
    if (!isset($taskslist)) {
      $user->createDefaultTaskslist($this->rc->config->get('default_taskslist_name', null));
    }
    return $args;
  }

  /**
   * Connect to smtp
   * Utilise les identifiants de la balp si nécessaire
   */
  public function smtp_connect($args) {
    if (!empty($_SESSION['m2_from_identity'])) {
      if (mel_logs::is(mel_logs::DEBUG))
        mel_logs::get_instance()->log(mel_logs::DEBUG, "mel::smtp_connect()");
      if (!empty($_SESSION['m2_uid_identity'])) {
        $args['smtp_user'] = $_SESSION['m2_uid_identity'];
      }
      else {
        $user = driver_mel::gi()->user();
        $user->email = $_SESSION['m2_from_identity'];
        if ($user->load('uid')) {
          $args['smtp_user'] = $user->uid;
        }
      }
    }
    return $args;
  }

  /**
   * After login user
   * Gestion des identités
   */
  public function login_after($args) {
    if (mel_logs::is(mel_logs::DEBUG))
      mel_logs::get_instance()->log(mel_logs::DEBUG, "mel::login_after()");

    if (isset($_GET['_goto_task'])) {
      $args['_task'] = trim(rcube_utils::get_input_value('_goto_task', rcube_utils::INPUT_GET));
    }
    else if ($args['_task'] == 'mail')  {
      $args['_task'] = $this->rc->config->get('default_task', 'mail');
    }
    // Gestion des identities de l'utilisateur
    $rc_identities = $this->rc->user->list_identities();
    $m2_identities = $this->m2_list_identities();
    $update_identities = array();
    $insert_identities = array();
    $delete_identities = array();
    // Parcour les identités
    foreach ($rc_identities as $rc_i) {
      if (isset($m2_identities[strtolower($rc_i['email'])])) {
        $m2_i = $m2_identities[strtolower($rc_i['email'])];
        if ($rc_i['email'] != $m2_i['email'] || $rc_i['realname'] != $m2_i['realname'] || $rc_i['uid'] != $m2_i['uid']) {
          $rc_i['email'] = $m2_i['email'];
          // Test si le nom n'a pas été modifié par l'utilisateur
          if ($this->m2_identity_shortname($rc_i['realname']) == $rc_i['name'])
            $rc_i['name'] = $m2_i['name'];
          $rc_i['realname'] = $m2_i['realname'];
          $rc_i['uid'] = $m2_i['uid'];
          $update_identities[$rc_i['identity_id']] = $m2_i;
        }
        // Vide le tableau pour lister ensuite les identities à créer
        unset($m2_identities[strtolower($rc_i['email'])]);
      }
      else {
        $delete_identities[] = $rc_i['identity_id'];
      }
    }
    $insert_identities = $m2_identities;
    // Insertion des identités
    foreach ($insert_identities as $insert_i) {
      $this->rc->user->insert_identity($insert_i);
    }
    // Mise à jour des identités
    foreach ($update_identities as $iid => $update_i) {
      $this->rc->user->update_identity($iid, $update_i);
    }
    // Suppression des identités
    foreach ($delete_identities as $delete_iid) {
      $this->rc->user->delete_identity($delete_iid);
    }
    return $args;
  }

  /**
   * ** IMAP ***
   */

  /**
   * Retoune le compte passé dans l'url
   *
   * @return string Account
   */
  public function m2_get_account() {
    if (mel_logs::is(mel_logs::DEBUG)) {
      mel_logs::get_instance()->log(mel_logs::DEBUG, "mel::m2_get_account()");
    }
    return array(
            "account" => $this->get_account
    );
  }

  /**
   * Handler for user preferences form (preferences_list hook)
   */
  function prefs_list($args) {
    if ($args['section'] == 'general') {
      // Load localization and configuration
      $this->add_texts('localization/');

      // Check that configuration is not disabled
      $dont_override = (array)$this->rc->config->get('dont_override', array());

      $key = 'mel_default_task';
      if (!in_array($key, $dont_override)) {
        $config_key = 'default_task';
        $field_id = "_" . $key;
        $value = $this->rc->config->get($config_key, 'mail');
        $input = new html_select(array(
          'name' => $field_id,
          'id' => $field_id,
        ));
        $list_tasks = $this->rc->config->get('list_tasks', ['mail', 'addressbook', 'settings']);
        foreach ($list_tasks as $task) {
          $input->add($this->gettext($task), $task);
        }

        $args['blocks']['main']['options'][$key] = array(
          'title' => html::label($field_id, rcube::Q($this->gettext($key))),
          'content' => $input->show($value),
        );
      }
    }
    else if ($args['section'] == 'mailbox') {
      // Load localization and configuration
      $this->add_texts('localization/');

      // Check that configuration is not disabled
      $dont_override = (array)$this->rc->config->get('dont_override', array());

      $key = 'mel_use_infinite_scroll';
      if (!in_array($key, $dont_override)) {
        $config_key = 'use_infinite_scroll';
        $field_id = "_" . $key;
        $is_checked = $this->rc->config->get($config_key, true);
        $input = new html_checkbox(array(
                'name' => $field_id,
                'id' => $field_id,
                'value' => 1
        ));
        $content = $input->show($is_checked);

        $args['blocks']['main']['options'][$key] = array(
                'title' => html::label($field_id, rcube::Q($this->gettext($key))),
                'content' => $content
        );
      }
    }
    return $args;
  }

  /**
   * Handler for user preferences save (preferences_save hook)
   */
  public function prefs_save($args) {
    if ($args['section'] == 'general') {
      // Check that configuration is not disabled
      $dont_override = ( array ) $this->rc->config->get('dont_override', array());
      $key = 'mel_default_task';
      if (!in_array($key, $dont_override)) {
        $config_key = 'default_task';
        $args['prefs'][$config_key] = rcube_utils::get_input_value('_' . $key, rcube_utils::INPUT_POST);
      }
    }
    else if ($args['section'] == 'mailbox') {
      // Check that configuration is not disabled
      $dont_override = ( array ) $this->rc->config->get('dont_override', array());
      $key = 'mel_use_infinite_scroll';
      if (! in_array($key, $dont_override)) {
        $config_key = 'use_infinite_scroll';
        $args['prefs'][$config_key] = rcube_utils::get_input_value('_' . $key, rcube_utils::INPUT_POST) ? true : false;
      }
    }
    return $args;
  }

  /**
   * Handler for user identity edit form
   */
  public function identity_form($args) {
    if (mel_logs::is(mel_logs::TRACE)) {
      mel_logs::get_instance()->log(mel_logs::TRACE, "mel::identity_form() args : " . var_export($args, true));
    }
    $realname = $args['form']['addressing']['content']['email'];
    $uid = $args['form']['addressing']['content']['email'];
    $realname['label'] = $this->gettext('realname');
    $uid['label'] = $this->gettext('uid');
    $args['form']['addressing']['content']['name']['label'] = $this->gettext('name identity');
    $args['form']['addressing']['content'] = array_slice($args['form']['addressing']['content'], 0, 1, true) + array(
            'realname' => $realname
    ) + array(
            'uid' => $uid
    ) + array_slice($args['form']['addressing']['content'], 1, count($args['form']['addressing']['content']) - 1, true);

    return $args;
  }

  /**
   * Handler for user identities list
   */
  public function identities_list($args) {
    if (mel_logs::is(mel_logs::TRACE)) {
      mel_logs::get_instance()->log(mel_logs::TRACE, "mel::identities_list() args : " . var_export($args, true));
    }
    $args['cols'][0] = 'name';
    return $args;
  }
  
  /**
   * Change From message header
   */
  function mail_headers($args) {
    if (isset($args['output']['from']) 
        && $args['output']['from']['html']) {
      $args['output']['from']['value'] = $this->rcmail_address_string($args['output']['from']['raw'], null, true, "/images/addcontact.png");
    }
        
    return $args;
  }
  
  /**
   * Decode address string and re-format it as HTML links
   */
  private function rcmail_address_string($input, $max=null, $linked=false, $addicon=null, $default_charset=null, $title=null)
  {
    global $PRINT_MODE;
    
    $a_parts = rcube_mime::decode_address_list($input, null, true, $default_charset);
    
    if (!count($a_parts)) {
      return $input;
    }
    
    $c   = count($a_parts);
    $j   = 0;
    $out = '';
    $allvalues  = array();
    $show_email = $this->rc->config->get('message_show_email');
    
    if ($addicon && !isset($_SESSION['writeable_abook'])) {
      $_SESSION['writeable_abook'] = $this->rc->get_address_sources(true) ? true : false;
    }
    
    foreach ($a_parts as $part) {
      $j++;
      
      $name   = $part['name'];
      $mailto = $part['mailto'];
      $string = $part['string'];
      $valid  = rcube_utils::check_email($mailto, false);
      
      // IDNA ASCII to Unicode
      if ($name == $mailto)
        $name = rcube_utils::idn_to_utf8($name);
        if ($string == $mailto)
          $string = rcube_utils::idn_to_utf8($string);
          $mailto = rcube_utils::idn_to_utf8($mailto);
          
          if ($PRINT_MODE) {
            $address = sprintf('%s &lt;%s&gt;', rcube::Q($name), rcube::Q($mailto));
          }
          else if ($valid) {
            if ($linked) {
              $attrs = array(
                  'href'    => 'mailto:' . $mailto,
                  'class'   => 'rcmContactAddress',
                  'onclick' => sprintf("return %s.command('compose','%s',this)",
                    rcmail_output::JS_OBJECT_NAME, rcube::JQ(format_email_recipient($mailto, $name))),
              );
              
              $attrs['title'] = $mailto;
              if (!$name) {
                $name = explode('@', $mailto, 2);
                $name = ucfirst(str_replace('.', ' ', $name[0])) . ' (' . $name[1] . ')';
              }
              $content =  html::tag('span', 'name', rcube::Q($name)) . html::tag('span', 'mailto', rcube::Q($mailto));
              
              $address = html::a($attrs, $content);
            }
            else {
              $address = html::span(array('title' => $mailto, 'class' => "rcmContactAddress"),
                rcube::Q($name ?: $mailto));
            }
            
            if ($addicon && $_SESSION['writeable_abook']) {
              $address .= html::a(array(
                  'href'    => "#add",
                  'title'   => $this->rc->gettext('addtoaddressbook'),
                  'class'   => 'rcmaddcontact',
                  'onclick' => sprintf("return %s.command('add-contact','%s',this)",
                    rcmail_output::JS_OBJECT_NAME, rcube::JQ($string)),
              ),
                html::img(array(
                    'src'   => $this->rc->output->abs_url($addicon, true),
                    'alt'   => "Add contact",
                    'class' => 'noselect',
                )));
            }
          }
          else {
            $address = $name ? rcube::Q($name) : '';
            if ($mailto) {
              $address = trim($address . ' ' . rcube::Q($name ? sprintf('<%s>', $mailto) : $mailto));
            }
          }
          
          $address = html::span('adr', $address);
          $allvalues[] = $address;
          
          if (!$moreadrs) {
            $out .= ($out ? ', ' : '') . $address;
          }
          
          if ($max && $j == $max && $c > $j) {
            if ($linked) {
              $moreadrs = $c - $j;
            }
            else {
              $out .= '...';
              break;
            }
          }
    }
    
    if ($moreadrs) {
      $label = rcube::Q($this->rc->gettext(array('name' => 'andnmore', 'vars' => array('nr' => $moreadrs))));
      
      if ($PRINT_MODE) {
        $out .= ' ' . html::a(array(
            'href'    => '#more',
            'class'   => 'morelink',
            'onclick' => '$(this).hide().next().show()',
        ), $label)
        . html::span(array('style' => 'display:none'), join(', ', $allvalues));
      }
      else {
        $out .= ' ' . html::a(array(
            'href'    => '#more',
            'class'   => 'morelink',
            'onclick' => sprintf("return %s.show_popup_dialog('%s','%s')",
              rcmail_output::JS_OBJECT_NAME,
              rcube::JQ(join(', ', $allvalues)),
              rcube::JQ($title))
        ), $label);
      }
    }
    
    return $out;
  }
  
  /**
   * Récupérer le nombre de mails non lus de l'utilisateur
   */
  public function get_unread_count() {
    $mbox = rcube_utils::get_input_value('_mbox', rcube_utils::INPUT_GET);
    if (!isset($mbox)) {
      $mbox = 'INBOX';
    }
    // Récupérer le nombre de mails non lus pour l'INBOX
    $unseen_count = $this->rc->storage->count($mbox, 'UNSEEN', true);
    // Gestion de l'account
    $account = mel::get_account();
    if (!isset($account) || empty($account)) {
      $account = $this->rc->get_user_name();
    }
    // send output
    header("Content-Type: application/json; charset=" . RCUBE_CHARSET);
    // Return the result to the ajax command
    echo json_encode(['unseen_count' => $unseen_count, '_account' => $account, '_mbox' => $mbox]);
    exit;
  }

  /**
   * *** LOGIN ****
   */
  /**
   * Supprimer la liste des serveurs
   */
  public function login_form($args) {
    $is_courrielleur = trim(rcube_utils::get_input_value('_courrielleur', rcube_utils::INPUT_GET));
    if (isset($is_courrielleur) && $is_courrielleur >= 1) {
      // Usage avec le courrielleur
      $task = trim(rcube_utils::get_input_value('_task', rcube_utils::INPUT_GET));
      if ($task != 'login') {
        header('Location: ?_task=login&_courrielleur=' . $is_courrielleur);
        exit();
      }

      $args['content'] = html::tag('h1', null, $this->gettext('You are disconnect from Roundcube')) . html::p(null, $this->gettext('You can close and reopen the tab')) . html::a(array(
              'href' => '?_task=login&_courrielleur=' . $is_courrielleur
      ), $this->gettext('Or you can clic here to relogin'));
    }
    else {
      if (mel_logs::is(mel_logs::DEBUG))
        mel_logs::get_instance()->log(mel_logs::DEBUG, "mel::login_form()");
      if (isset($_POST['_user']))
        $username = trim(rcube_utils::get_input_value('_user', rcube_utils::INPUT_POST));
      else
        $username = "";

      // save original url
      $url = rcube_utils::get_input_value('_url', rcube_utils::INPUT_POST);
      if (empty($url) && !preg_match('/_(task|action)=logout/', $_SERVER['QUERY_STRING']))
        $url = $_SERVER['QUERY_STRING'];

      $input_task = new html_hiddenfield(array(
              'name' => '_task',
              'value' => 'login'
      ));
      $input_action = new html_hiddenfield(array(
              'name' => '_action',
              'value' => 'login'
      ));
      $input_timezone = new html_hiddenfield(array(
              'name' => '_timezone',
              'id' => 'rcmlogintz',
              'value' => '_default_'
      ));
      $input_url = new html_hiddenfield(array(
              'name' => '_url',
              'id' => 'rcmloginurl',
              'value' => $url
      ));
      $input_login = new html_inputfield(array(
              'name' => '_user',
              'id' => 'rcmloginuser',
              'size' => '40',
              'autocapitalize' => 'off',
              'autocomplete' => 'on'
      ));
      $input_password = new html_passwordfield(array(
              'name' => '_pass',
              'id' => 'rcmloginpwd',
              'size' => '40',
              'autocapitalize' => 'off',
              'autocomplete' => 'off'
      ));
      if (!$this->rc->config->get('hide_keep_login_button', false)) {
          $checkbox_keeplogin = new html_checkbox(array(
              'name' => '_keeplogin',
              'id' => 'rcmloginkeep',
              'value' => 'keeplogin',
              'title' => $this->gettext('computer_private_title')
          ));
      }

      $keeplogin = "";
      $class_tr = "";
      $login_div = "";
      // Si le cookie est présent on modifie l'interface
      if (isset($_COOKIE['roundcube_login']) && !$this->rc->config->get('hide_keep_login_button', false)) {
        $login = explode('###', $_COOKIE['roundcube_login']);
        if ($username == "") {
          $username = $login[0];
        }
        if (isset($login[1])) {
          $cn = $login[1];
        }
        else {
          $cn = $username;
        }
        $keeplogin = "keeplogin";
        $class_tr = "hidden_login_input";
        $login_div = html::div(array(
                'class' => 'login_div'
        ), html::div(array(
                'class' => 'img'
        ), " ") . html::div(array(
                'class' => 'name'
        ), $cn) . html::a(array(
                'id' => 'rcmchangeuserbutton',
                'href' => '#'
        ), $this->gettext('change_user')));
      }
      else if (isset($_POST['_keeplogin']) && !$this->rc->config->get('hide_keep_login_button', false)) {
        $keeplogin = "keeplogin";
      }
      
      if ($this->rc->output->get_env('ismobile')) {
        if ($this->rc->config->get('hide_keep_login_button', false)) {
            $keeplogin_html = '';
        }
        else {
            $keeplogin_html = html::div(array(
                'class' => $class_tr
            ), $checkbox_keeplogin->show($keeplogin) . html::label(array(
                'for' => 'rcmloginkeep'
            ), $this->gettext('device_private')));
        }
        $args['content'] = $input_task->show() . $input_action->show() . $input_timezone->show() . $input_url->show() . $login_div . html::div(array(
                'id' => 'formlogintable'
        ), html::div(array(
                'class' => $class_tr
        ), html::label(array(
                'for' => 'rcmloginuser'
        ), $this->rc->gettext('username'))) . html::div(array(
                'class' => $class_tr
        ), $input_login->show($username)) . html::div(null, html::label(array(
                'for' => 'rcmloginpwd'
        ), $this->rc->gettext('password'))) . html::div(null, $input_password->show()) . $keeplogin_html) . html::p(array(
                'class' => 'formbuttons'
        ), html::tag('input', array(
                'id' => 'rcmloginsubmit',
                'class' => 'button mainaction',
                'type' => 'submit',
                'value' => $this->rc->gettext('login')
        )));
      }
      else {
        $table = new html_table(array(
                'id' => 'formlogintable'
        ));
        $table->add_row(array(
                'class' => $class_tr
        ));
        $table->add(array(
                'class' => 'title'
        ), html::label(array(
                'for' => 'rcmloginuser'
        ), $this->rc->gettext('username')));
        $table->add(array(
                'class' => 'input'
        ), $input_login->show($username));
        $table->add_row();
        $table->add(array(
                'class' => 'title'
        ), html::label(array(
                'for' => 'rcmloginpwd'
        ), $this->rc->gettext('password')));
        $table->add(array(
                'class' => 'input'
        ), $input_password->show());
        $table->add_row(array(
                'class' => $class_tr
        ));
        if (!$this->rc->config->get('hide_keep_login_button', false)) {
            $table->add(array(
                'class' => 'input'
            ), $checkbox_keeplogin->show($keeplogin));
            $table->add(array(
                'class' => 'title'
            ), html::label(array(
                'for' => 'rcmloginkeep'
            ), $this->gettext('computer_private')));
        }

        $args['content'] = $input_task->show() . $input_action->show() . $input_timezone->show() . $input_url->show() . $login_div . $table->show() . html::p(array(
                'class' => 'formbuttons'
        ), html::tag('input', array(
                'id' => 'rcmloginsubmit',
                'class' => 'button mainaction',
                'type' => 'submit',
                'value' => $this->rc->gettext('login')
        )));
        if ($this->rc->config->get('show_no_bal_message', true) && mel::is_internal()) {
          $args['content'] .= html::div(array(), html::a(array(
                  "href" => "./changepassword/index.php"
          ), $this->gettext('no bal')));
        }
      }
    }

    return $args;
  }

  /**
   * ****** Cache data **********
   */

  /**
   * Initialisation du cache en session
   */
  protected static function _InitSessionCache() {
    if (!isset($_SESSION[self::CACHE_KEY])) {
      $_SESSION[self::CACHE_KEY] = array();
    }
    return $_SESSION[self::CACHE_KEY];
  }

  /**
   * Positionne le cache en session
   *
   * @param array $cache
   */
  protected static function _SetSessionCache($cache) {
    $_SESSION[self::CACHE_KEY] = $cache;
  }

  /**
   * Positionne en cache la valeur
   * 
   * @param string $key Identifiant du cache
   * @param string|array $value Valeur a stocker en cache
   * @param boolean $reset RAZ du timer de cache (defaut false)
   * @param int $duration [Optionnel] Durée du cache pour cette clé
   */
  public static function setCache($key, &$value, $reset = false, $duration = null) {
    $cache = self::_InitSessionCache();
    if (!isset($duration)) {
      $default_duration = rcmail::get_instance()->config->get("cache_default-duration", 300);
      $duration = rcmail::get_instance()->config->get("cache_$key-duration", $default_duration);
    }
    if (!$reset && isset($cache[$key])) {
      $cache[$key]['value'] = serialize($value);
    }
    else {
      $cache[$key] = [
        'expire'  => time() + $duration,
        'value' => serialize($value),
      ];
    }
    self::_SetSessionCache($cache);
  }

  /**
   * Le cache est-il toujours positionné ?
   * 
   * @param string $key Identifiant du cache
   * 
   * @return boolean
   */
  public static function issetCache($key) {
    $cache = self::_InitSessionCache();
    if (isset($cache[$key]) && isset($cache[$key]['expire']) && $cache[$key]['expire'] > time()) {
      return true;
    }
    else if (isset($cache[$key])) {
      unset($cache[$key]);
      self::_SetSessionCache($cache);
    }
    return false;
  }

  /**
   * Récupère la valeur en cache
   * 
   * @param $key Identifiant du cache
   * 
   * @return mixed $value, null si non trouvée
   */
  public static function &getCache($key) {
    $cache = self::_InitSessionCache();
    if (isset($cache[$key]) && isset($cache[$key]['expire']) && $cache[$key]['expire'] > time()) {
      $ret = unserialize($cache[$key]['value']);
      if ($ret !== false) {
        return $ret;
      }
    }
    else if (isset($cache[$key])) {
      unset($cache[$key]);
      self::_SetSessionCache($cache);
    }
    return null;
  }

  /**
   * Supprime la valeur en cache
   * 
   * @param $key Identifiant du cache
   */
  public static function unsetCache($key) {
    $cache = self::_InitSessionCache();
    if (isset($cache[$key])) {
      unset($cache[$key]);
      self::_SetSessionCache($cache);
    }
  }

  /**
   * Récupère le username en fonction du compte dans l'url ou de la session
   *
   * @return string
   */
  public function get_username() {
    if (!isset($this->user_name)) {
      $this->set_user_properties();
    }
    return $this->user_name;
  }
  /**
   * Récupère l'uid de la boite, sans l'objet de partage si c'est une boite partagée
   *
   * @return string
   */
  public function get_user_bal() {
    if (!isset($this->user_bal)) {
      $this->set_user_properties();
    }
    return $this->user_bal;
  }
  /**
   * Récupère l'uid de l'objet de partage
   *
   * @return string
   */
  public function get_share_objet() {
    if (!isset($this->user_objet_share)) {
      $this->set_user_properties();
    }
    return $this->user_objet_share;
  }
  /**
   * Récupère l'host de l'utilisateur
   *
   * @return string
   */
  public function get_host() {
    if (!isset($this->user_host)) {
      $this->set_user_properties();
    }
    return $this->user_host;
  }
  /**
   * ****** PRIVATE *********
   */
  /**
   * Définition des propriétées de l'utilisateur
   */
  private function set_user_properties() {
    if (!empty($this->get_account)) {
      // Récupération du username depuis l'url
      $this->user_name = urldecode($this->get_account);
      $inf = explode('@', $this->user_name);
      $this->user_objet_share = urldecode($inf[0]);
      $this->user_host = $inf[1] ?: null;
      $user = driver_mel::gi()->getUser($this->user_objet_share, false);
      if ($user->is_objectshare) {
        $this->user_bal = $user->objectshare->mailbox_uid;
      }
      else {
        $this->user_bal = $this->user_objet_share;
      }
    }
    else {
      // Récupération du username depuis la session
      $this->user_name = $this->rc->get_user_name();
      $this->user_objet_share = $this->rc->user->get_username('local');
      $this->user_host = $this->rc->user->get_username('host');
      $this->user_bal = $this->user_objet_share;
    }
  }
  /**
   * Permet de récupérer toutes les identités de l'utilisateur depuis le LDAP
   *
   * @return array
   */
  private function m2_list_identities() {
    $user = driver_mel::gi()->getUser();
    $_objects = $user->getObjectsSharedEmission();
    $identities = [];
    foreach ($_objects as $_object) {
      // Si on a un mail principal
      if (isset($_object->email_send) && !empty($_object->email_send)) {
        $mails = [$_object->email_send];
        // Si on a une liste de mail en plus du principal
        if (isset($_object->email_send_list) && count($_object->email_send_list) > 1) {
          array_merge($mails, $_object->email_send_list);
        }
      }
      // Sinon on utilise la liste
      else if (isset($_object->email_send_list) && !empty($_object->email_send_list)) {
        $mails = $_object->email_send_list;
      }
      foreach ($mails as $email) {
        $identity = [];
        $identity['name'] = $this->m2_identity_shortname($_object->fullname);
        $identity['realname'] = $_object->fullname;
        $identity['email'] = $email;
        $identity['uid'] = $_object->uid;
        $identities[strtolower($email)] = $identity;
      }
    }
    // Récuperation des information de l'utilisateur
    // Si on a un mail principal
    if (isset($user->email_send) && !empty($user->email_send)) {
      $mails = [$user->email_send];
      // Si on a une liste de mail en plus du principal
      if (isset($user->email_send_list) && count($user->email_send_list) > 1) {
        array_merge($mails, $user->email_send_list);
      }
    }
    // Sinon on utilise la liste
    else if (isset($user->email_send_list) && !empty($user->email_send_list)) {
      $mails = $user->email_send_list;
    }
    foreach ($mails as $email) {
      $identity = [];
      $identity['name'] = $user->fullname;
      $identity['realname'] = $user->fullname;
      $identity['email'] = $email;
      $identity['uid'] = $user->uid;
      $identities[strtolower($email)] = $identity;
    }
    // retourne la liste des identities
    return $identities;
  }
  /**
   * Génération du nom court de l'identité en fonction du nom
   *
   * @param string $name
   * @return string
   */
  private function m2_identity_shortname($name) {
    if (strpos($name, ' emis par ') !== false) {
      $name = explode(' emis par ', $name);
      $name = $name[0] . " (partagée)";
    }
    elseif (strpos($name, ' - ') !== false) {
      $name = explode(' - ', $name);
      $name = $name[0];
    }
    return $name;
  }

  /**
   * Défini si on est dans une instance interne ou extene de l'application
   * Permet la selection de la bonne url
   */
  public static function is_internal() {
    if (isset($_GET['internet'])) {
      return false;
    }
    return rcmail::get_instance()->config->get('is_internal', false);
  }
  /**
   * Retourne l'adresse ip
   * @return string
   * @private
   */
  private function _get_address_ip() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
      $ip = $_SERVER['HTTP_CLIENT_IP'];
      $ip = "[".$_SERVER['REMOTE_ADDR']."]/[$ip]";
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
      $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
      $ip = "[".$_SERVER['REMOTE_ADDR']."]/[$ip]";
    } else {
      $ip = $_SERVER['REMOTE_ADDR'];
      $ip = "[$ip]/[".$_SERVER['REMOTE_ADDR']."]";
    }
    return $ip;
  }
}
