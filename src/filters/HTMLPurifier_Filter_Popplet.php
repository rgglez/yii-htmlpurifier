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
 * HTMLPurifier_Filter_Popplet permite la inclusión de código de Popplet.
 *
 * <object width="460" height="460" data="http://popplet.com/app/Popplet_Alpha.swf?page_id=2919068&amp;em=1" type="application/x-shockwave-flash"><param name="allowFullScreen" value="true" /><param name="allowscriptaccess" value="always" /><param name="src" value="http://popplet.com/app/Popplet_Alpha.swf?page_id=2919068&amp;em=1" /><param name="allowfullscreen" value="false" /></object>
 *
 * @author rodolfo
 */
class HTMLPurifier_Filter_Popplet extends HTMLPurifier_Filter
{
   public $name = 'Popplet';

   public function preFilter($html, $config, $context)
   {
      // \1 = page
      // \2 = width
      // \3 = height
      // \4 = page
      $pre_regex  = '/<object data="http:\/\/popplet\.com\/app\/Popplet_Alpha\.swf\?page_id=([\d]+)&amp;em=1" type="application\/x-shockwave-flash" width="([\d]+)" height="([\d]+)">';
      $pre_regex .= '<param[^>]+\/>';
      $pre_regex .= '<param[^>]+\/>';
      $pre_regex .= '<param name="src" value="http:\/\/popplet\.com\/app\/Popplet_Alpha\.swf\?page_id=([\d]+)&amp;em=1" \/>';
      $pre_regex .= '<param[^>]+\/><\/object>/s';

      $pre_replace = '<a class="popplet-embed" href="\1!\2!\3!\4">Popplet</a>';
      $html = preg_replace($pre_regex, $pre_replace, $html);
      return $html;
   }

   public function postFilter($html, $config, $context)
   {
      $post_regex = '|<a class="popplet-embed" href="([^!]+)!([^!]+)!([^!]+)!([^!]+)">Popplet</a>|';
      return preg_replace_callback($post_regex, array($this, 'postFilterCallback'), $html);
   }

   protected function postFilterCallback($matches)
   {
      // \1 = page
      // \2 = width
      // \3 = height
      // \4 = page
      $popplet  = '<object width="'.$matches[2].'" height="'.$matches[3].'">';
      $popplet .= '<param value="http://popplet.com/app/Popplet_Alpha.swf?page_id='.$matches[1].'&em=1" name="movie"></param>';
      $popplet .= '<param value="true" name="allowFullScreen"></param>';
      $popplet .= '<param value="always" name="allowscriptaccess"></param>';
      $popplet .= '<embed src="http://popplet.com/app/Popplet_Alpha.swf?page_id='.$matches[4].'&em=1" height="'.$matches[3].'" width="'.$matches[2].'" allowfullscreen="false" allowscriptaccess="always" type="application/x-shockwave-flash"></embed></object>';

      return $popplet;
   }
}
