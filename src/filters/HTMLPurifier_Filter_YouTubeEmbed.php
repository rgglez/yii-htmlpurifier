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

class HTMLPurifier_Filter_YouTubeEmbed extends HTMLPurifier_Filter
{
   public $name = 'YouTubeEmbed';

   public function preFilter($html, $config, $context)
   {
      $pre_regex1  = '#<iframe src="(https://www\.youtube\.com/embed/[^">]+)" width="([\d]+)" height="([\d]+)" frameborder="0" allowfullscreen></iframe>#s';
      $pre_regex2  = '#<iframe src="(https://www\.youtube\.com/embed/[^">]+)" width="([\d]+)" height="([\d]+)" frameborder="0" allowfullscreen=""></iframe>#s';
      $pre_regex3  = '#<iframe src="(https://www\.youtube\.com/embed/[^">]+)" width="([\d]+)" height="([\d]+)" frameborder="0" allowfullscreen="allowfullscreen"></iframe>#s';
      $pre_regex4  = '#<iframe src="(//www\.youtube\.com/embed/[^">]+)" width="([\d]+)" height="([\d]+)" allowfullscreen="allowfullscreen"></iframe>#s';
      $pre_regex4  = '#<iframe src="(https://www\.youtube\.com/embed/[^">]+)" width="([\d]+)" height="([\d]+)" allowfullscreen="allowfullscreen"></iframe>#s';
      $pre_regex5  = '#<iframe title="" src="(https://www\.youtube\.com/embed/[^\?]+)\?wmode=opaque&amp;theme=dark" width="([\d]+)" height="([\d]+)" frameborder="0" allowfullscreen="allowfullscreen"></iframe>#s';
      $pre_regex6  = '#<iframe title="[^"]+" src="(https://www\.youtube\.com/embed/[^">]+)" width="([\d]+)" height="([\d]+)" frameborder="0" allowfullscreen="allowfullscreen"></iframe>#s';
      $pre_regex7  = '#<iframe width="([\d]+)" height="([\d]+)" src="(https://www\.youtube\.com/embed/[^">]+)" frameborder="0" gesture="media" allow="encrypted-media" allowfullscreen></iframe>#';
      $pre_regex8  = '#<iframe width="([\d]+)" height="([\d]+)" src="(https://www\.youtube\.com/embed/[^">]+)" frameborder="0" gesture="media" allow="encrypted-media" allowfullscreen=""></iframe>#';
      $pre_regex9  = '#<iframe width="([\d]+)" height="([\d]+)" src="(https://www\.youtube\.com/embed/[^">]+)" frameborder="0" gesture="media" allow="encrypted-media" allowfullscreen="allowfullscreen"></iframe>#';
      $pre_regex10 = '#<iframe width="([\d]+)" height="([\d]+)" src="(https://www\.youtube\.com/embed/[^">]+)" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen=""></iframe>#';      
      $pre_replace1 = '<a class="youtube-embeded" href="\1-\2-\3">YouTube</a>';
      $pre_replace2 = '<a class="youtube-embeded" href="\3-\1-\2">YouTube</a>';
      $html = preg_replace($pre_regex1, $pre_replace1, $html);
      $html = preg_replace($pre_regex2, $pre_replace1, $html);
      $html = preg_replace($pre_regex3, $pre_replace1, $html);
      $html = preg_replace($pre_regex4, $pre_replace1, $html);
      $html = preg_replace($pre_regex5, $pre_replace1, $html);
      $html = preg_replace($pre_regex6, $pre_replace1, $html);
      $html = preg_replace($pre_regex7, $pre_replace2, $html);
      $html = preg_replace($pre_regex8, $pre_replace2, $html);
      $html = preg_replace($pre_regex9, $pre_replace2, $html);
      $html = preg_replace($pre_regex10, $pre_replace2, $html);
      return $html;
   }

   public function postFilter($html, $config, $context)
   {
      $post_regex = '#<a class="youtube-embeded" href="([\w|:]*//www\.youtube\.com/embed/[^">]+)-([\d]+)-([\d]+)">YouTube</a>#';
      return preg_replace_callback($post_regex, [$this, 'postFilterCallback'], $html);
   }

   protected function postFilterCallback($matches)
   {
      return '<iframe width="'.$matches[2].'" height="'.$matches[3].'" frameborder="0" allowfullscreen="allowfullscreen" src="'.$matches[1].'"></iframe>';
   }
}