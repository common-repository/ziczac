<?
/*
Plugin Name: ZicZac.it Plugin!
Version: 2.0.2
Plugin URI: http://ziczac.it/extra/wordpress-plugin/
Description: Aggiungi il pulsante di <a href="http://ziczac.it" target="_blank">ZicZac</a> per far votare i tuoi post.
Author: micz
Author URI: http://micz.it/
*/

/*  Copyright 2008  ZicZac.it  (email : redazione@ziczac.it)

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
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
$opt_pos=get_option('ziczac_bgcolor');
$opt_pos=get_option('ziczac_pos');
$opt_scomp=get_option('ziczac_scomp');  
$opt_tipo=get_option('ziczac_tipo');

function add_ziczac_btn()
{
	global $post,$opt_tipo,$opt_bgc;
	if(is_page())return;
	$txt_bgc='';
	if($opt_bgc!='')$txt_bgc='zz_bgc=\''.$opt_bgc.'\';';
	if($opt_tipo!=0)$txt_tipo='zz_t=\''.$opt_tipo.'\';';
	return '<div class="ziczacp"><script type="text/javascript">'.$txt_bgc.'zz_url=encodeURIComponent(\''.get_permalink($post->ID).'\');'.$txt_tipo.'zz_title=encodeURIComponent(\''.addslashes(get_the_title($post->ID)).' '.get_option('ziczac_title_suffix').'\');</script><script src="http://ziczac.it/a/e/zz.js" type="text/javascript"></script></div>';
}

function set_ziczac_options(){
	add_option('ziczac_bgcolor','');
	add_option('ziczac_title_suffix','');
	add_option('ziczac_tipo',1);
	add_option('ziczac_pos',0);
	add_option('ziczac_scomp',0);
}

function unset_ziczac_options(){
	delete_option('ziczac_bgcolor');
	delete_option('ziczac_title_suffix');
	delete_option('ziczac_tipo');
	delete_option('ziczac_pos');
	delete_option('ziczac_scomp');
}

function insert_ziczac_button($content)
{
global $opt_pos,$opt_scomp;
  $out_content='';
  switch($opt_pos){
	default:
	case 0:	//Prima
		$out_content=add_ziczac_btn().$content;
		break;
	case 1:	//Dopo
		$out_content=$content.add_ziczac_btn();
		break;
	case 2:	//Template
		$out_content=$content;
		break;
  }
  if($opt_scomp){
    if(strpos($content,'more-link')===false){
      return $out_content;
    }else{
      return $content;
    }
  }else{
    return $out_content;
  }
}

function ziczac_modify_menu(){
	add_options_page('ZicZac','ZicZac',8,basename(__FILE__),'ziczac_options_subpanel');
}

function ziczac_options_subpanel(){
	global $_POST;
	$txt_flash = '';
	if (isset($_POST['zz_bgc'])) { 
		update_option('ziczac_bgcolor',$_POST['zz_bgc']);
		$txt_flash = 'Opzioni salvate.';
	}
	if (isset($_POST['zz_title_suffix'])) { 
		update_option('ziczac_title_suffix',$_POST['zz_title_suffix']);
		$txt_flash = 'Opzioni salvate.';
	}
	if (isset($_POST['zz_tipo'])) { 
		update_option('ziczac_tipo',$_POST['zz_tipo']);
		$txt_flash = 'Opzioni salvate.';
	}
	if (isset($_POST['zz_pos'])) { 
		update_option('ziczac_pos',$_POST['zz_pos']);
		$txt_flash = 'Opzioni salvate.';
	}
	if (isset($_POST['zz_scomp'])) { 
		update_option('ziczac_scomp',$_POST['zz_scomp']);
		$txt_flash = 'Opzioni salvate.';
	}else{
	  update_option('ziczac_scomp',0);
		if (isset($_POST['ou']))$txt_flash = 'Opzioni salvate.';
	}
	if ($txt_flash != '') echo '<div id="message"class="updated fade"><p>' . $txt_flash . '</p></div>';
	echo '<div class="wrap">';
	echo '<h2>Opzioni ZicZac</h2>' ;
	echo '<p>Imposta le opzioni di visualizzazione del pulsante di <a href="http://ziczac.it/"><b>ZicZac</b></a> sul tuo sito.</p>
	<form action="" method="post"><input type="hidden" name="ou" value="1"/>
	<table class="form-table">
<tbody>
<tr valign="top">
<th scope="row">Tipo di pulsante</th>
<td>
<table><tr><td valign="top" style="border:0;padding-right:0;"><input type="radio" name="zz_tipo" value="1" '.(get_option('ziczac_tipo')==1?'checked':'').'/></td><td valign="top" style="border:0;padding-left:0;"><img src="http://ziczac.it/static/zzpunti.png"></td>
<td valign="top" style="border:0;padding-right:0;"><input type="radio" name="zz_tipo" value="2" '.(get_option('ziczac_tipo')==2?'checked':'').'/></td><td valign="top" style="border:0;padding-left:0;"><img src="http://ziczac.it/static/zzpunti_o.png"></td></tr></table>
</td></tr>
<tr valign="top">
<th scope="row">Posizione del pulsante</th>
<td>
<input type="radio" name="zz_pos" value="0"'.(get_option('ziczac_pos')==0?' checked="checked"':'').'/> <b>Prima</b> del contenuto del post<br/>
<input type="radio" name="zz_pos" value="1"'.(get_option('ziczac_pos')==1?' checked="checked"':'').'/> <b>Dopo</b> il contenuto del post<br/>
<input type="radio" name="zz_pos" value="2"'.(get_option('ziczac_pos')==2?' checked="checked"':'').'/> Solo con la funzione <code>ziczac_it()</code> direttamente nel codice del template<br/>
</td></tr>
<tr valign="top">
<th scope="row">Colore sfondo riquadro</th>
<td>
<input type="text" name="zz_bgc" value="' . get_option('ziczac_bgcolor') . '" size="40" maxlenght="6" /><br/>
<small><em>(Espresso in <a href="http://en.wikipedia.org/wiki/HTML_color_names" target="_blank">RGB</a> senza # iniziale)</em></small>
</td></tr>
<tr valign="top">
<th scope="row">Testo da aggiungere dopo il titolo del post</th>
<td>
<input type="text" name="zz_title_suffix" value="' . get_option('ziczac_title_suffix') . '" size="40" maxlenght="50" />
</td></tr>
<tr valign="top">
<th scope="row">Esclusioni</th>
<td>
<input type="checkbox" name="zz_scomp" value="1"'.(get_option('ziczac_scomp')==1?' checked="checked"':'').'/> Mostra il pulsante solo se viene visualizzato tutto il post
</td></tr>
</tbody>
</table>
<p class="submit"><input type="submit" value="Salva" /></p></form>';
echo '</div>';
}

function strip_js_script($text){
global $opt_bgcolor;
	($opt_bgcolor!='')?$txt_bgc='zz_bgc':$txt_bgc='zz_url';
  return preg_replace('@'.$txt_bgc.'=.*?\);@si','',$text);
}

function ziczac_it(){ //Da usare nel template
global $opt_pos;
 if($opt_pos==2) echo add_ziczac_btn();
 return;
}

register_activation_hook(__FILE__,'set_ziczac_options');
register_deactivation_hook(__FILE__,'unset_ziczac_options');
add_action('admin_menu','ziczac_modify_menu');
add_filter('get_the_excerpt','strip_js_script');
add_action('the_content','insert_ziczac_button');
if($opt_comp==0)add_action('the_excerpt','insert_ziczac_button');
?>