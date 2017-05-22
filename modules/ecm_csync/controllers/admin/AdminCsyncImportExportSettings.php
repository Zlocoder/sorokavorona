<?php
include_once(dirname(__FILE__).'/../../ecm_csync.php');
class AdminCsyncImportExportSettingsController extends AdminController
{
    public $log;
    public function __construct()
    {

        $this->bootstrap = true;
        parent::__construct();


    }
     public function initContent()
    {
        parent::initContent();
      }
    public function postProcess()
    {
        if (Tools::isSubmit('addFile') ) {
            $uploaddir = _PS_UPLOAD_DIR_;
           //d($_FILES['upfile']);
            if (isset($_FILES['upfile'])&& $_FILES['upfile']['type'] == 'text/xml') {
                $uploadfile = $uploaddir.$_FILES['upfile']['name'];
                $filetype   = substr($uploadfile, strlen($uploadfile) - 3);
                if ($filetype == "xml") {
                    //print $uploadfile;
                    move_uploaded_file($_FILES['upfile']['tmp_name'], $uploadfile);
                    chmod($uploadfile , 0777 );
                    $this->context->smarty->assign(array(
                    'success'=> $this->succes_('Файл '.$_FILES['upfile']['name'].' успешно загружен')
                ));
                }
                else {
                    $this->context->smarty->assign(array(
                    'error'=> $this->error_('Файл не формата xml')
                ));
                }
            }
            else {
                $this->context->smarty->assign(array(
                    'error'=> $this->error_('Файл не в  формата xml')
                ));
            }
        }
    }
    public function renderList()
    {

        $this->module = new Ecm_csync();
        $path           = _PS_MODULE_DIR_."ecm_csync";
        include($path. '/init/defines_inc.php');
        $import         = 'http://'.htmlspecialchars($_SERVER['HTTP_HOST'], ENT_COMPAT, 'UTF-8').__PS_BASE_URI__.'modules/ecm_csync/1c_exchange.php?type=catalog&mode=import&filename=import.xml';
        $offers         = 'http://'.htmlspecialchars($_SERVER['HTTP_HOST'], ENT_COMPAT, 'UTF-8').__PS_BASE_URI__.'modules/ecm_csync/1c_exchange.php?type=catalog&mode=import&filename=offers.xml';
        $orders         = 'http://'.htmlspecialchars($_SERVER['HTTP_HOST'], ENT_COMPAT, 'UTF-8').__PS_BASE_URI__.'modules/ecm_csync/1c_exchange.php?type=sale&mode=query';
        $genuid         = 'http://'.htmlspecialchars($_SERVER['HTTP_HOST'], ENT_COMPAT, 'UTF-8').__PS_BASE_URI__.'modules/ecm_csync/export_to_1C.php?type=genuid';
        $catalog        = 'http://'.htmlspecialchars($_SERVER['HTTP_HOST'], ENT_COMPAT, 'UTF-8').__PS_BASE_URI__.'modules/ecm_csync/export_to_1C.php?type=catalog';
        $stocks         = 'http://'.htmlspecialchars($_SERVER['HTTP_HOST'], ENT_COMPAT, 'UTF-8').__PS_BASE_URI__.'modules/ecm_csync/export_to_1C.php?type=offers';
        $sale           = 'http://'.htmlspecialchars($_SERVER['HTTP_HOST'], ENT_COMPAT, 'UTF-8').__PS_BASE_URI__.'modules/ecm_csync/export_to_1C.php?type=sale';
        $ansfile        = 'http://'.htmlspecialchars($_SERVER['HTTP_HOST'], ENT_COMPAT, 'UTF-8').__PS_BASE_URI__.'modules/ecm_csync/1c_exchange.php?type=sale&mode=file&filename=';
        $status_export_ = $this->module->checked($status_export);
        $files          = scandir(_PS_UPLOAD_DIR_,0);
        foreach ($files as $file) {
            $file_exc = explode(".",$file);
            if (@$file_exc[1] == 'xml')
            $file_name[] = $file;
        }
        //d($file_name);
        $this->context->smarty->assign(
            array(
                'import'       => $import,
                'offers'       => $offers,
                'orders'       => $orders,
                'genuid'       => $genuid,
                'catalog'      => $catalog,
                'stocks'       => $stocks,
                'sale'         => $sale,
                'ansfile'      => $ansfile,
                'files'        => $file_name,
                'status_export'=> $status_export_,
            )
        );
        $more = $this->module->display($path, 'views/displayImportExportSettings.tpl');
        return $more.parent::renderList();
    }
	public
    function succes_($text)
    {
        return '
        <div class="bootstrap">
        <div class="alert alert-success">
        <btn btn-default button type="btn btn-default button" class="close" data-dismiss="alert">×</btn btn-default button>
        '.$text.'
        </div>
        </div>
        ';
    }
    public
    function error_($text)
    {
        return '
        <div class="bootstrap">
        <div class="alert alert-danger">
        <btn btn-default button type="btn btn-default button" class="close" data-dismiss="alert">×</btn btn-default button>
        '.$text.'
        </div>
        </div>
        ';
    }
}

