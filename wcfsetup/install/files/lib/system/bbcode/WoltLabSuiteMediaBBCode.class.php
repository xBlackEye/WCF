<?php
namespace wcf\system\bbcode;
use wcf\data\media\ViewableMedia;
use wcf\system\message\embedded\object\MessageEmbeddedObjectManager;
use wcf\system\request\LinkHandler;
use wcf\system\WCF;
use wcf\util\StringUtil;

/**
 * Parses the [wsm] bbcode tag.
 * 
 * @author	Alexander Ebert
 * @copyright	2001-2018 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	WoltLabSuite\Core\System\Bbcode
 * @since       3.0
 */
class WoltLabSuiteMediaBBCode extends AbstractBBCode {
	/**
	 * forces media links to be linked to the frontend
	 * after it has been set to `true`, it should be set to `false` again as soon as possible
	 * @var	boolean
	 */
	public static $forceFrontendLinks = false;
	
	/**
	 * @inheritDoc
	 */
	public function getParsedTag(array $openingTag, $content, array $closingTag, BBCodeParser $parser) {
		$mediaID = (!empty($openingTag['attributes'][0])) ? intval($openingTag['attributes'][0]) : 0;
		if (!$mediaID) {
			return '';
		}
		
		/** @var ViewableMedia $media */
		$media = MessageEmbeddedObjectManager::getInstance()->getObject('com.woltlab.wcf.media', $mediaID);
		if ($media !== null && $media->isAccessible()) {
			if ($parser->getOutputType() == 'text/html') {
				if ($media->isImage) {
					$thumbnailSize = (!empty($openingTag['attributes'][1])) ? $openingTag['attributes'][1] : 'original';
					$float = (!empty($openingTag['attributes'][2])) ? $openingTag['attributes'][2] : 'none';
					
					WCF::getTPL()->assign([
						'float' => $float,
						'media' => $media->getLocalizedVersion(MessageEmbeddedObjectManager::getInstance()->getActiveMessageLanguageID()),
						'thumbnailSize' => $thumbnailSize
					]);
					
					return WCF::getTPL()->fetch('mediaBBCodeTag', 'wcf', [
						'mediaLink' => $this->getLink($media),
						'thumbnailLink' => $thumbnailSize !== 'original' ? $this->getThumbnailLink($media, $thumbnailSize) : ''
					]);
				}
				
				return StringUtil::getAnchorTag($this->getLink($media), $media->getTitle());
			}
			else if ($parser->getOutputType() == 'text/simplified-html') {
				return StringUtil::getAnchorTag($this->getLink($media), $media->getTitle());
			}
			
			return StringUtil::encodeHTML($this->getLink($media));
		}
		
		return '';
	}
	
	/**
	 * Returns the link to the given media file (while considering the value of `$forceFrontendLinks`).
	 * 
	 * @param	ViewableMedia	$media		linked media file
	 * @return	string				link to media file
	 */
	protected function getLink(ViewableMedia $media) {
		if (self::$forceFrontendLinks) {
			return LinkHandler::getInstance()->getLink('Media', [
				'forceFrontend' => 'true',
				'object' => $media
			]);
		}
		
		return $media->getLink();
	}
	
	/**
	 * Returns the thumbnail link to the given media file (while considering the value of `$forceFrontendLinks`).
	 * 
	 * @param	ViewableMedia	$media		linked media file
	 * @param	string	$thumbnailSize		thumbnail size
	 * @return	string				link to media thumbnail
	 */
	protected function getThumbnailLink(ViewableMedia $media, $thumbnailSize) {
		// use `Media::getThumbnailLink()` to validate thumbnail size
		$thumbnailLink = $media->getThumbnailLink($thumbnailSize);
		
		if (self::$forceFrontendLinks) {
			if (!$this->{$thumbnailSize.'ThumbnailType'}) {
				return $this->getLink($media);
			}
			
			return LinkHandler::getInstance()->getLink('Media', [
				'forceFrontend' => 'true',
				'object' => $media,
				'thumbnail' => $thumbnailSize
			]);
		}
		
		return $thumbnailLink;
	}
}
