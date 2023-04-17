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

Yii::import('application.extensions.ehtmlpurifier.filters.*');

/**
 * EHtmlPurifier extiende CHtmlPurifier para hacerlo más personalizable.
 *
 * @author rodolfo
 */
class EHtmlPurifier extends CHtmlPurifier
{
	/**
	 * Purifies the HTML content by removing malicious code.
	 * @param string $content the content to be purified.
	 * @return string the purified content
	 */
	public function purify($content)
	{
		$purifier=new HTMLPurifier($this->options);
		$purifier->config->set('Cache.SerializerPath', Yii::app()->getRuntimePath());
      $purifier->config->set('HTML.MathML', true);
      $purifier->config->set('Attr.AllowedFrameTargets', ['_blank']);
      $purifier->config->set('Filter.Custom', [
          new HTMLPurifier_Filter_YouTubeEmbed(),
          new HTMLPurifier_Filter_GoogleMaps(),
          new HTMLPurifier_Filter_Screenr(),
          new HTMLPurifier_Filter_Voki(),
          new HTMLPurifier_Filter_EducaPlay(),
          new HTMLPurifier_Filter_Scribd(),
          new HTMLPurifier_Filter_Prezi(),
          new HTMLPurifier_Filter_ExamTime(),
          new HTMLPurifier_Filter_Popplet(),
      ]);
		return $purifier->purify($content);
	}   
}