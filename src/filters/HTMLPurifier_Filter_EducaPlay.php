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
 * HTMLPurifier_Filter_EducaPlay permite incluir desde EducaPlay.
 *
 * Ejemplo Adivinanza:
 *
 * <iframe src='http://www.educaplay.com/es/recursoseducativos/993590/html5/adivinanza.htm' width='795' height='690' frameborder='0'></iframe><a href='http://www.educaplay.com/es/recursoseducativos/993590/adivinanza.htm'>Adivinanza</a>
 *
 */
class HTMLPurifier_Filter_EducaPlay extends HTMLPurifier_Filter
{
   public $name = 'EducaPlay';

   public function preFilter($html, $config, $context)
   {
      $pre_regex1 = '#<iframe width="([\d]+)" height="([\d]+)" src="http://www\.educaplay\.com/es/recursoseducativos/([\d]+)/html5/([\w]+)\.htm"[^>]+></iframe>#';
      $pre_regex2 = '#<iframe src="http://www\.educaplay\.com/es/recursoseducativos/([\d]+)/html5/([\w]+)\.htm" width="([\d]+)" height="([\d]+)" frameborder="0"></iframe>#';
      $pre_replace1 = '<a class="educaplay-embed" href="\1-\2-\3-\4">Educaplay</a>';
      $pre_replace2 = '<a class="educaplay-embed" href="\3-\4-\1-\2">Educaplay</a>';
      $html = preg_replace($pre_regex1, $pre_replace1, $html);
      $html = preg_replace($pre_regex2, $pre_replace2, $html);
      return $html;
   }

   public function postFilter($html, $config, $context)
   {
      $post_regex = '#<a class="educaplay-embed" href="([\d]+)-([\d]+)-([\d]+)-([\w]+)">Educaplay</a>#';
      return preg_replace_callback($post_regex, array($this, 'postFilterCallback'), $html);
   }

   protected function postFilterCallback($matches)
   {
      $educaplay = '<iframe width="'.$matches[1].'" height="'.$matches[2].'" src="http://www.educaplay.com/es/recursoseducativos/'.$matches[3].'/html5/'.$matches[4].'.htm" frameborder="0"></iframe>';
      return $educaplay;
   }
}