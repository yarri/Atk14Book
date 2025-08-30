<?php
#[\AllowDynamicProperties]
class ApplicationController extends Atk14Controller{

	/**
	 * @var Menu14
	 */
	var $breadcrumbs;

	function index(){
		$this->_execute_action("error404");
	}

	function error404(){
		// Old URIs
		if($this->request->get() && preg_match('/^\/czech(|\/.*)$/',$this->request->getUri(),$matches)){
			return $this->_redirect_to($matches[1],["moved_permanently" => true]);
		}

		$this->page_title = $this->breadcrumbs[] = _("Page not found");
		$this->response->setStatusCode(404);
		$this->template_name = "application/error404";
	}

	function _initialize(){
		$this->_prepend_before_filter("application_before_filter");
		$this->_append_after_filter("application_after_filter");

		if(!$this->rendering_component){
			// Definujme toto jako posledni krok v _initialize()!
			// Docilime toho, ze filtr _begin_database_transaction() bude zavolan uplne jako prvni before filter
			// a _end_database_transaction() zase jako posledni after filtr.
			$this->_prepend_before_filter("begin_database_transaction");
			$this->_append_after_filter("end_database_transaction");
		}
	}

	function _before_render(){
		global $ATK14_GLOBAL;

		if(!isset($this->tpl_data["breadcrumbs"]) && isset($this->breadcrumbs)){
			$this->tpl_data["breadcrumbs"] = $this->breadcrumbs;
		}
	}

	function _application_before_filter(){
		global $ATK14_GLOBAL;

		$this->response->setContentType("text/html");
		$this->response->setContentCharset(DEFAULT_CHARSET);
		$this->response->setHeader("Cache-Control","private, max-age=0, must-revalidate");
		$this->response->setHeader("Pragma","no-cache");

		// security headers
		$this->response->setHeader("X-Frame-Options","SAMEORIGIN"); // avoiding clickjacking attacks; "SAMEORIGIN", "DENY"
		$this->response->setHeader("X-XSS-Protection","1; mode=block");
		$this->response->setHeader("Referrer-Policy","same-origin"); // "same-origin", "strict-origin", "strict-origin-when-cross-origin"...
		$this->response->setHeader("X-Content-Type-Options","nosniff");
		//$this->response->setHeader("Content-Security-Policy","default-src 'self' data: 'unsafe-inline' 'unsafe-eval'");

		$this->response->setHeader("X-Powered-By","ATK14 Framework");

		if(
			(PRODUCTION && $this->request->get() && !$this->request->xhr() && ("www.".$this->request->getHttpHost()==ATK14_HTTP_HOST || $this->request->getHttpHost()=="www.".ATK14_HTTP_HOST)) ||
			(defined("REDIRECT_TO_CORRECT_HOSTNAME_AUTOMATICALLY") && constant("REDIRECT_TO_CORRECT_HOSTNAME_AUTOMATICALLY") && $this->request->getHttpHost()!=ATK14_HTTP_HOST)
		){
			// redirecting from http://example.com/xyz to http://www.example.com/xyz
			$scheme = (defined("REDIRECT_TO_SSL_AUTOMATICALLY") && constant("REDIRECT_TO_SSL_AUTOMATICALLY")) ? "https" : $this->request->getScheme();
			return $this->_redirect_to("$scheme://".ATK14_HTTP_HOST.$this->request->getUri(),array("moved_permanently" => true));
		}

		if(!$this->request->ssl() && defined("REDIRECT_TO_SSL_AUTOMATICALLY") && constant("REDIRECT_TO_SSL_AUTOMATICALLY") && !in_array("$this->namespace/$this->controller",["/remote_tests"])){
			return $this->_redirect_to_ssl();
		}

		$this->breadcrumbs = new Menu14();

		$this->tpl_data["current_year"] = date("Y");

		$this->tpl_data["search_form"] = Atk14Form::GetInstanceByFilename("searches/search_form.php",$this);
	}

	function _application_after_filter(){
		if(DEVELOPMENT){
			$bar = Tracy\Debugger::getBar();
			$bar->addPanel(new DbMolePanel($this->dbmole));
			$bar->addPanel(new TemplatesPanel());
    }
	}

	function _begin_database_transaction(){
		$this->dbmole->begin(array(
			"execute_after_connecting" => true
		));
	}

	function _end_database_transaction(){
		$this->dbmole->commit();
	}
}
