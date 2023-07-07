<?php

if (!defined('_PS_VERSION_')) {
    exit;
}

class Modulecrud extends Module
{
    public function __construct()
    {
        $this->name = 'modulecrud';
        $this->tab = 'administration';
        $this->version = '1.0.0';
        $this->author = 'Andy Campo';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = [
            'min' => '1.6.0',
            'max' => '1.7.9',
        ];
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Módulo CRUD');
        $this->description = $this->l('Módulo que permite realizar transacciones CRUD (CreateReadUpdateDelete) en la base de datos.');

        $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');

        if (!Configuration::get('MYMODULE_NAME')) {
            $this->warning = $this->l('No name provided');
        }
    }

    public function install()
    {
        return parent::install() && $this->registerHook('displayAfterProductThumbs') && $this->installDB();
    }

    public function installDB()
    {
        Db::getInstance()->execute('CREATE TABLE IF NOT EXISTS '._DB_PREFIX_.$this->name.'_text (
            id int(11) NOT NULL AUTO_INCREMENT,
            texto VARCHAR(255),
            PRIMARY KEY (id)
          ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8');
        return true;
    }
    


public function uninstallDB()
{
    Db::getInstance()->execute('DROP TABLE IF EXISTS '._DB_PREFIX_.$this->name.'_text');
    return true;
}


    public function uninstall()
    {
        return parent::uninstall() && $this->uninstallDB();
    }

    public function getContent(){
        $base_url = AdminController::$currentIndex . '&configure=' . $this->name . '&token=' . Tools::getAdminTokenLite('AdminModules');
        $this->urls = [
            'add' => $base_url . '&mod_action=add',
            'edit' => $base_url . '&mod_action=edit&id=',
        ];
    
        $action = Tools::getValue('mod_action');
        return $this->postProcess() . $this->renderAdmin($action);
    }
    
    
    private function postProcess()
    {
        if (Tools::isSubmit('add')) {
            $insert = [
                'texto' => pSQL(Tools::getValue('texto_nuevo'))
            ];
            Db::getInstance()->insert($this->name.'_text', $insert);
            
            return $this->displayConfirmation($this->l('Guardado correctamente'));
        }
    }
    
    
    private function getForm($action)
{
    $helper = new HelperForm();
    $helper->module = $this;
    $helper->name_controller = $this->name;
    $helper->identifier = $this->identifier;
    $helper->token = Tools::getAdminTokenLite('AdminModules');
    $helper->languages = $this->context->controller->getLanguages();
    $helper->currentIndex = AdminController::$currentIndex . '&configure=' . $this->name;
    $helper->default_form_language = $this->context->controller->default_form_language;
    $helper->allow_employee_form_lang = $this->context->controller->allow_employee_form_lang;
    $helper->title = $this->displayName;

    $helper->submit_action = $action;   
    
    if ($action == 'add') {
        $helper->fields_value = [
            'text' => '',
        ];
        $form[] = [
            'form' => [
                'legend' => [
                    'title' => $this->l('Añadir texto nuevo'),
                ],
                'input' => [
                    [
                        'type' => 'text', 
                        'name' => 'texto_nuevo',
                        'label' => $this->l('Cambiar texto'),
                    ],
                ],
                'submit' => [
                    'title' => $this->l('Add'),
                ],
            ]
        ];
    }

    if ($action == 'edit') {
        $form[] = [
            'form' => [
                'legend' => [
                    'title' => $this->l('Cambiar el nombre de la variable'),
                ],
                'input' => [
                    [
                        'type' => 'text', 
                        'name' => 'texto_nuevo',
                        'label' => $this->l('Cambiar texto'),
                    ],
                ],
                'submit' => [
                    'title' => $this->l('Edit'),
                ],
            ]
        ];
    }

    $helper->fields_value = [
        'texto_nuevo' => $this->l('Hello world')
    ];

    

    return $helper->generateForm($form);
}

private function renderAdmin($action)
{
 if ($action == '' || $action == 'home') {
    $this->context->smarty->assign([
        'textos'=> $this->getTextos(),
        'urls'=> $this->urls,
    ]);
    return $this->context->smarty->fetch($this->local_path . 'views/templates/admin/admin.tpl');
}
return $this->getForm($action);
}

    private function getTextos() 
    {
        return Db::getInstance()->executeS('SELECT 
        * FROM ' ._DB_PREFIX_.$this->name.'_text');
    }


    public function hookDisplayAfterProductThumbs()
    {
      /*   $texto = 'Hello world'; */
        $this->context->smarty->assign([
            'texto' => $texto,
        ]);
        return $this->display(__FILE__, 'product.tpl');
    }    
}
