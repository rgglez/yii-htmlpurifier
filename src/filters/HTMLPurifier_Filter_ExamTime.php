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
 * HTMLPurifier_Filter_ExamTime permite la inclusión de código de ExamTime.
 *
 * <iframe width="100%" height="600" style="border: 1px solid #ccc;" scrolling="no" src="https://www.goconqr.com/es/p/4323539-Glosario-de-Filosof-a-flash_card_decks?frame=true" allowfullscreen="" webkitallowfullscreen="" mozallowfullscreen="" oallowfullscreen="" msallowfullscreen=""></iframe><a href="https://www.goconqr.com/es/flashcards">Conjunto de Fichas creado con GoConqr por luzmaria.padilla</a>
 *
 * @author rodolfo
 */
class HTMLPurifier_Filter_ExamTime extends HTMLPurifier_Filter
{
   public $name = 'ExamTime';

   public function preFilter($html, $config, $context)
   {
      // \1 = width
      // \2 = height
      // \3 = src
      // \4 = texto
      $pre_regex1 = '!<iframe width="([^"]+)" height="([^"]+)" style="border: 1px solid #ccc;" scrolling="no" src="([^"]+)" allowfullscreen="" webkitallowfullscreen="" mozallowfullscreen="" oallowfullscreen="" msallowfullscreen=""></iframe><a href="https://www.goconqr.com/es/flashcards">([^<]+)</a>!s';
      $pre_regex2 = '!<iframe style="border: 1px solid #ccc;" src="([^"]+)" width="([^"]+)" height="([^"]+)" scrolling="no" allowfullscreen="allowfullscreen"></iframe><a href="https://www.goconqr.com/es/flashcards">([^<]+)</a>!s';
      $pre_replace1 = '<a class="examtime-embed" title="\3@\1@\2@\4">ExamTime</a>';
      $pre_replace2 = '<a class="examtime-embed" title="\2@\3@\1@\4">ExamTime</a>';
      $html = preg_replace($pre_regex1, $pre_replace1, $html);
      $html = preg_replace($pre_regex2, $pre_replace2, $html);
      return $html;
   }

   public function postFilter($html, $config, $context)
   {
      $post_regex = '|<a class="examtime-embed" title="([^@]+)@([^@]+)@([^@]+)@([^@]+)">ExamTime</a>|';
      return preg_replace_callback($post_regex, array($this, 'postFilterCallback'), $html);
   }

   protected function postFilterCallback($matches)
   {
      // matches:
      // \1 = src
      // \2 = width
      // \3 = height
      // \4 = texto
      $et = '<iframe width="'.$matches[1].'" height="'.$matches[2].'" style="border: 1px solid #ccc;" scrolling="no" src="'.$matches[3].'" allowfullscreen="" webkitallowfullscreen="" mozallowfullscreen="" oallowfullscreen="" msallowfullscreen=""></iframe><a href="https://www.goconqr.com/es/flashcards">'.$matches[4].'</a>';
      return $et;
   }
}
