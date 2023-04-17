<?php
/*
MIT License

Copyright (c) 2023 Rodolfo González González

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
*/

/**
 * HTMLPurifier_Filter_Voki permite insertar código de voki.com
 *
 * <object id="widget_name" width="200" height="267" data="https://vhss-d.oddcast.com/vhss_editors/voki_player.swf?doc=https%3A%2F%2Fvhss-d.oddcast.com%2Fphp%2Fvhss_editors%2Fgetvoki%2Fchsm=ba970cb9a8279fd3827b0573414fe447%26sc=12296764" type="application/x-shockwave-flash"><param name="quality" value="high" /><param name="allowScriptAccess" value="always" /><param name="allowNetworking" value="all" /><param name="wmode" value="transparent" /><param name="allowFullScreen" value="true" /><param name="src" value="https://vhss-d.oddcast.com/vhss_editors/voki_player.swf?doc=https%3A%2F%2Fvhss-d.oddcast.com%2Fphp%2Fvhss_editors%2Fgetvoki%2Fchsm=ba970cb9a8279fd3827b0573414fe447%26sc=12296764" /><param name="allowscriptaccess" value="always" /><param name="allownetworking" value="all" /><param name="allowfullscreen" value="true" /><param name="pluginspage" value="http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash" /></object>
 */
class HTMLPurifier_Filter_Voki extends HTMLPurifier_Filter
{
   public $name = 'Voki';

   public function preFilter($html, $config, $context)
   {
      // \1 = url1
      // \2 = width
      // \2 = height
      // \4 = url2
      $pre_regex  = '!<object id="widget_name" data="https://vhss-d\.oddcast\.com/vhss_editors/voki_player\.swf\?doc=([^"]+)" type="application/x-shockwave-flash" width="([\d]+)" height="([\d]+)">';
      $pre_regex .= '<param[^>]+/>';
      $pre_regex .= '<param[^>]+/>';
      $pre_regex .= '<param[^>]+/>';
      $pre_regex .= '<param[^>]+/>';
      $pre_regex .= '<param[^>]+/>';
      $pre_regex .= '<param name="src" value="https://vhss-d\.oddcast\.com/vhss_editors/voki_player\.swf\?doc=([^"]+)"[^>]+/>';
      $pre_regex .= '<param[^>]+/>';
      $pre_regex .= '<param[^>]+/>';
      $pre_regex .= '<param[^>]+/>';
      $pre_regex .= '<param[^>]+/></object>!s';

      $pre_replace = '<a class="voki-embed" title="\1!\2!\3!\4">Voki</a>';
      $html = preg_replace($pre_regex, $pre_replace, $html);
      return $html;
   }

   public function postFilter($html, $config, $context)
   {
      $post_regex = '|<a class="voki-embed" title="([^!]+)!([^!]+)!([^"]+)!([^"]+)">Voki</a>|';
      return preg_replace_callback($post_regex, array($this, 'postFilterCallback'), $html);
   }

   protected function postFilterCallback($matches)
   {
      // matches:
      // \1 = url1
      // \2 = width
      // \3 = height
      // \4 = url2
      $voki  = '<object id="widget_name" width="'.$matches[2].'" height="'.$matches[3].'" data="https://vhss-d.oddcast.com/vhss_editors/voki_player.swf?doc='.$matches[1].'" type="application/x-shockwave-flash">';
      $voki .= '<param name="quality" value="high" />';
      $voki .= '<param name="allowScriptAccess" value="always" />';
      $voki .= '<param name="allowNetworking" value="all" />';
      $voki .= '<param name="wmode" value="transparent" />';
      $voki .= '<param name="allowFullScreen" value="true" />';
      $voki .= '<param name="src" value="https://vhss-d.oddcast.com/vhss_editors/voki_player.swf?doc='.$matches[4].'" />';
      $voki .= '<param name="allowscriptaccess" value="always" />';
      $voki .= '<param name="allownetworking" value="all" />';
      $voki .= '<param name="allowfullscreen" value="true" />';
      $voki .= '<param name="pluginspage" value="http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash" />Voki</object>';

      return $voki;
   }
}