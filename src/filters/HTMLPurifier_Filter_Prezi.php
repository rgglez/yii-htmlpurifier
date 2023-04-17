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
 * HTMLPurifier_Filter_Prezi permite la inclusión de código de prezi.
 *
 * <iframe id="iframe_container" width="550" height="400" frameborder="0" webkitallowfullscreen="" mozallowfullscreen="" allowfullscreen="" src="https://prezi.com/embed/oooue9ds7mwd/?bgcolor=ffffff&amp;lock_to_path=0&amp;autoplay=0&amp;autohide_ctrls=0&amp;landing_data=bHVZZmNaNDBIWnNjdEVENDRhZDFNZGNIUE43MHdLNWpsdFJLb2ZHanI0VTc1RUh5ZmpjZThlNlhMSFBwYjdRU1RnPT0&amp;landing_sign=qBWR7NAmd-lUxe37nRiKCI1_YCLhpiBtp2TLhzCDiKQ"></iframe>
 *
 * @author rodolfo
 */
class HTMLPurifier_Filter_Prezi extends HTMLPurifier_Filter
{
   public $name = 'Prezi';

   public function preFilter($html, $config, $context)
   {
      // \1 = src
      // \2 = width
      // \3 = height
      $pre_regex = '!<iframe id="iframe_container" src="([^"]+)" width="([^"]+)" height="([^"]+)" frameborder="0" allowfullscreen="allowfullscreen"></iframe>!s';
      $pre_replace = '<a class="prezi-embed" title="\1!\2!\3">Prezi</a>';
      $html = preg_replace($pre_regex, $pre_replace, $html);
      return $html;
   }

   public function postFilter($html, $config, $context)
   {
      $post_regex = '|<a class="prezi-embed" title="([^!]+)!([^!]+)!([^!]+)">Prezi</a>|';
      return preg_replace_callback($post_regex, array($this, 'postFilterCallback'), $html);
   }

   protected function postFilterCallback($matches)
   {
      // matches:
      // \1 = src
      // \2 = width
      // \3 = height
      $prezi = '<iframe id="iframe_container" width="'.$matches[2].'" height="'.$matches[3].'" frameborder="0" webkitallowfullscreen="" mozallowfullscreen="" allowfullscreen="" src="'.$matches[1].'"></iframe>';
      return $prezi;
   }
}
