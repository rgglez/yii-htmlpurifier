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
 * HTMLPurifier_Filter_Scribd permite incluir desde Scribd.
 *
 * Ejemplo:
 *
 * <iframe class="scribd_iframe_embed" src="//www.scribd.com/embeds/136473220/content?start_page=1&view_mode=scroll&access_key=key-2e3bo4hckb1q3lv48elh&show_recommendations=true" data-auto-height="false" data-aspect-ratio="0.706896551724138" scrolling="no" id="doc_83796" width="100%" height="600" frameborder="0"></iframe>
 *
 */
class HTMLPurifier_Filter_Scribd extends HTMLPurifier_Filter
{
   public $name = 'Scribd';

   public function preFilter($html, $config, $context)
   {
      // 1 = id
      // 2 = src
      // 3 = width
      // 4 = height
      // 5 = data auto height
      // 6 = data aspect ratio
      $pre_regex = '#<iframe id="([\w]+)" class="scribd_iframe_embed" src="([^"]+)" width="([^"]+)" height="([^"]+)" frameborder="0" scrolling="no" data-auto-height="([^"]+)" data-aspect-ratio="([^"]+)"></iframe>#';
      $pre_replace = '<a class="scribd-embed" title="\1@\2@\3@\4@\5@\6">Scribd</a>';
      $html = preg_replace($pre_regex, $pre_replace, $html);
      return $html;
   }

   public function postFilter($html, $config, $context)
   {
      $post_regex = '#<a class="scribd-embed" title="([^|]+)@([^|]+)@([^|]+)@([^|]+)@([^|]+)@([^|]+)">Scribd</a>#';
      return preg_replace_callback($post_regex, array($this, 'postFilterCallback'), $html);
   }

   protected function postFilterCallback($matches)
   {
      // 1 = id
      // 2 = src
      // 3 = width
      // 4 = height
      // 5 = data auto height
      // 6 = data aspect ratio
      $scribd = '<iframe id="'.$matches[1].'" width="'.urldecode($matches[3]).'" height="'.urldecode($matches[4]).'" src="'.$matches[2].'" data-auto-height="'.$matches[5].'" data-aspect-ratio="'.$matches[6].'" scrolling="no" frameborder="0"></iframe>';
      return $scribd;
   }
}