<?php
    /*
    RosCMS - ReactOS Content Management System
    Copyright (C) 2005  Klemens Friedl <frik85@reactos.org>

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 675 Mass Ave, Cambridge, MA 02139, USA.
    */

/**
 * class HTML
 * 
 */
abstract class HTML
{

  protected $page_start; // start time of page generation
  protected $title; 
  protected $css_files=array(); 
  protected $js_files=array(); 

  public function __construct( $page_title = '', $page_css = 'roscms' )
  {
    // get page title and register css files
    $this->title = $page_title;
    if ($page_css == 'roscms'){
      $this->register_css('style_v3.css');
    }
    else {
      $this->register_css('style.css');
      $this->register_css('logon.css');
    }
    
    // this page was generated in ...
    $roscms_gentime = explode(' ',microtime()); 
    $this->page_start = $roscms_gentime[1] + $roscms_gentime[0]; 

    $this->build();
  }


  // build page
  protected function build()
  {
    $this->header();
    $this->body();
    $this->footer();
  }

  /**
   *
   *
   * @access public
   */
  abstract protected function body( );


  /**
   *
   *
   * @param string page_title
   * @param string page_css
   * @access public
   */
  protected function header( )
  {
    global $roscms_intern_webserver_pages;
    global $roscms_intern_webserver_roscms;
    global $roscms_langres;

    // output header
    echo_strip( '
      <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
      <html lang="'.$roscms_langres['lang_code'].'">
      <head>
        <title>ReactOS '.(($this->title!=='') ? '- '.$this->title : '').'</title>
        <meta http-equiv="Content-Type" content="text/html; charset='.$roscms_langres['charset'].'" />
        <meta http-equiv="Pragma" content="no-cache" />
        <meta name="Copyright" content="ReactOS Foundation" />
        <meta name="generator" content="RosCMS" />
        <meta name="Content-language" content="'.$roscms_langres['lang_code'].'" />
        <meta name="Robots" content="noindex,nofollow" />
        <meta http-equiv="Content-Script-Type" content="text/javascript" />
        <meta http-equiv="Content-Style-Type" content="text/css" />
        <link rel="SHORTCUT ICON" href="../favicon.ico" />');

    // link css & js files (use register_* methods)
    foreach($this->css_files as $file) {
      if ($file['condition'] === false) {
        echo '<link href="'.$roscms_intern_webserver_roscms.'css/'.$file['name'].'" type="text/css" rel="stylesheet" />';
      }
      else {
        echo '<!--[if '.$file['condition'].']<link href="'.$roscms_intern_webserver_roscms.'css/'.$file['name'].'" type="text/css" rel="stylesheet" /><![endif]-->';
      }
    }
    foreach($this->js_files as $file) {
      echo '<script src="'.$roscms_intern_webserver_roscms.'js/'.$file.'" type="text/javascript"></script>';
    }

    echo_strip('
      </head>
      <body>
      <div id="top">
        <div id="topMenu"> 
          <p style="color: white;text-align: center;"> 
            <a href="'.$roscms_intern_webserver_pages.'?page=index">'.$roscms_langres['Home'].'</a> <span>|</span>
            <a href="'.$roscms_intern_webserver_pages.'?page=about">'.$roscms_langres['Info'].'</a> <span>|</span>
            <a href="'.$roscms_intern_webserver_pages.'?page=community">'.$roscms_langres['Community'].'</a> <span>|</span>
            <a href="'.$roscms_intern_webserver_pages.'?page=dev">'.$roscms_langres['Dev'].'</a> <span>|</span>
            <a href="'.$roscms_intern_webserver_roscms.'?page=user">'.$roscms_langres['myReactOS'].'</a>
          </p>
        </div>
      </div>');
  } // end of member function header


  /**
   *
   *
   * @access public
   */
  protected function footer( )
  {
    global $roscms_extern_brand, $roscms_extern_version_detail;

    // This page was generated in ...
    $gentime = explode(' ',microtime()); 
    $page_end = $gentime[1] + $gentime[0]; 
    $page_time = number_format($page_end - $this->page_start, 4, '.', ''); 

    // footer information
    echo_strip('
      <hr />
      <div id="footer" style="text-align:center;">
        '.$roscms_extern_brand.' '.$roscms_extern_version_detail.' - (c) 2005-2007 Klemens Friedl<br />
        <br />
        Page generated in '.$page_time.' seconds
      </div>
      </body>
      </html>');
  } // end of member function footer


  /**
   * register a css file to be included in the header
   *
   * @access protected
   */
  protected function register_css( $name, $condition = false )
  {
    $this->css_files[] = array('name'=>$name, 'condition'=>$condition);
  } // end of member function register_css


  /**
   * register a javascript file to be included in the header
   *
   * @access protected
   */
  protected function register_js( $name )
  {
    $this->js_files[] = $name;
  } // end of member function register_css

} // end of HTML
?>
