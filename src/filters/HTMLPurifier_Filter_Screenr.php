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
 * HTMLPurifier_Filter_Screenr
 *
 * <iframe src="http://www.screenr.com/embed/dqGH" width="650" height="396" frameborder="0"></iframe>
 *
 * @author rodolfo
 */
class HTMLPurifier_Filter_Screenr  extends HTMLPurifier_Filter
{
   public $name = 'Screenr';

   public function preFilter($html, $config, $context)
   {
      $pre_regex = '#<iframe[^>]+src="(http://www\.screenr\.com/embed/[^"]+)"[^>]+></iframe>#';
      $pre_replace = '<a class="screenr-embed" href="\1">Screenr</a>';
      $x = preg_replace($pre_regex, $pre_replace, $html);
      return $x;
   }

   public function postFilter($html, $config, $context)
   {
      $post_regex = '#<a class="screenr-embed" href="(http://www\.screenr\.com/embed/[^"]+)">Screenr</a>#';
      $x = preg_replace_callback($post_regex, array($this, 'postFilterCallback'), $html);
      return $x;
   }

   protected function postFilterCallback($matches)
   {
      return '<iframe src="'.$matches[1].'" width="650" height="396" frameborder="0"></iframe>';
   }
}