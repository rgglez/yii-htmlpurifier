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
 * HTMLPurifier_Filter_GoogleMaps permite incluir desde Google Maps.
 *
 * <iframe width="425" height="350" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://www.google.com/maps?t=m&amp;q=Cath%C3%A9drale+Notre-Dame&amp;dg=opt&amp;ie=UTF8&amp;hq=Cath%C3%A9drale+Notre-Dame&amp;hnear=&amp;ll=48.852795,2.352338&amp;spn=0.013624,0.027608&amp;output=embed"></iframe><br /><small><a href="https://www.google.com/maps?t=m&amp;q=Cath%C3%A9drale+Notre-Dame&amp;dg=opt&amp;ie=UTF8&amp;hq=Cath%C3%A9drale+Notre-Dame&amp;hnear=&amp;ll=48.852795,2.352338&amp;spn=0.013624,0.027608&amp;source=embed" style="color:#0000FF;text-align:left">Ver mapa más grande</a></small>
 */
class HTMLPurifier_Filter_GoogleMaps extends HTMLPurifier_Filter
{
   public $name = 'GoogleMaps';

   public function preFilter($html, $config, $context)
   {
      $pre_regex1 = '#<iframe width="([\d]+)" height="([\d]+)" src="(https://www\.google\.com/maps\?[^"]+)" [^>]+></iframe>#s';
      $pre_regex2 = '#<iframe[^>]+src="(https://www\.google\.com/maps\?[^"]+)" width="([\d]+)" height="([\d]+)" [^>]+></iframe>#s';
      $pre_replace1 = '<a class="googlemaps-embed" title="\1{\2{\3">Google Maps</a>';
      $pre_replace2 = '<a class="googlemaps-embed" title="\2{\3{\1">Google Maps</a>';
      $html = preg_replace($pre_regex1, $pre_replace1, $html);
      $html = preg_replace($pre_regex2, $pre_replace2, $html);
      return $html;
   }

   public function postFilter($html, $config, $context)
   {
      $post_regex = '#<a class="googlemaps-embed" title="([\d]+){([\d]+){([^"]+)">Google Maps</a>#';
      return preg_replace_callback($post_regex, array($this, 'postFilterCallback'), $html);
   }

   protected function postFilterCallback($matches)
   {
      return '<iframe width="'.$matches[1].'" height="'.$matches[2].'" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="'.$matches[3].'"></iframe>';
   }
}