<?php

/*
 * This file was part of the package bk2k/bootstrap-package.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Rintisch\PictureExtend\ViewHelpers;

use TYPO3\CMS\Core\Resource\Exception\ResourceDoesNotExistException;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Service\ImageService;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;

/**
 * InlineSvgViewHelper
 */
class InlineSvgViewHelper extends AbstractViewHelper
{
    use CompileWithRenderStatic;

    /**
     * @var bool
     */
    protected $escapeOutput = false;

    /**
     * Initialize arguments.
     *
     * @throws \TYPO3Fluid\Fluid\Core\ViewHelper\Exception
     */
    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument('image', 'object', 'a FAL object');
        $this->registerArgument('src', 'string', 'a path to a file');
        $this->registerArgument('class', 'string', 'Css class for the svg');
        $this->registerArgument('width', 'string', 'Width of the svg.', false);
        $this->registerArgument('height', 'string', 'Height of the svg.', false);
        $this->registerArgument('fillWith', 'string', 'Color to fill the svg');
    }

    /**
     * @param array $arguments
     * @param \Closure $renderChildrenClosure
     * @param RenderingContextInterface $renderingContext
     * @return string
     * @throws \Exception
     */
    public static function renderStatic(
        array $arguments,
        \Closure $renderChildrenClosure,
        RenderingContextInterface $renderingContext
    ) {
        $src = (string)$arguments['src'];
        $image = $arguments['image'];

        if (($src === '' && $image === null) || ($src !== '' && $image !== null)) {
            throw new \Exception('You must either specify a string src or a File object.', 1530601100);
        }

        try {
            $imageService = self::getImageService();
            $image = $imageService->getImage($src, $image, false);
            if ($image->getProperty('extension') !== 'svg') {
                return '';
            }

            $svgContent = $image->getContents();
            $svgContent = trim(preg_replace('/<script[\s\S]*?>[\s\S]*?<\/script>/i', '', $svgContent));

            // Exit if file does not contain content
            if ($svgContent === '') {
                return '';
            }

            $svgElement = simplexml_load_string($svgContent);
            if (!$svgElement instanceof \SimpleXMLElement) {
                return '';
            }

            // Calculate height from width (needed for IE11)
            //get relevant values from viewbox
            $height = (int)$arguments['height'];
            $width = (int)$arguments['width'];
            if ($arguments['width'] && !$arguments['height']) {
                $viewBoxValues = explode(' ', (string)$svgElement['viewBox']);
                $viewBoxWidth = $viewBoxValues[2];
                $viewBoxHeight = $viewBoxValues[3];
                $height = (int)floor((int)$arguments['width'] * $viewBoxHeight / $viewBoxWidth);
            }

            // Override css class
            $class = (string)$arguments['class'];
            $class = htmlspecialchars($class ?? '');
            $svgElement = self::setAttribute($svgElement, 'class', $class);
            $width = intval($width) > 0 ? (string) intval($width) : null;
            $svgElement = self::setAttribute($svgElement, 'width', $width);
            $height = intval($height) > 0 ? (string) intval($height) : null;
            $svgElement = self::setAttribute($svgElement, 'height', $height);

            // Overwrite any fill attributes with given value
            if ($arguments['fillWith']) {
                $filledElements = $svgElement->xpath('//*[@fill]');
                if ($filledElements) {
                    foreach ($filledElements as $filledElement) {
                        self::setAttribute($filledElement, 'fill', (string)$arguments['fillWith']);
                    }
                }
            }

            // remove xml version tag
            $domXml = dom_import_simplexml($svgElement);
            return $domXml->ownerDocument->saveXML($domXml->ownerDocument->documentElement);
        } catch (ResourceDoesNotExistException $e) {
            // thrown if file does not exist
            throw new \Exception($e->getMessage(), 1530601100, $e);
        } catch (\UnexpectedValueException $e) {
            // thrown if a file has been replaced with a folder
            throw new \Exception($e->getMessage(), 1530601101, $e);
        } catch (\RuntimeException $e) {
            // RuntimeException thrown if a file is outside of a storage
            throw new \Exception($e->getMessage(), 1530601102, $e);
        } catch (\InvalidArgumentException $e) {
            // thrown if file storage does not exist
            throw new \Exception($e->getMessage(), 1530601103, $e);
        }
    }

    protected static function setAttribute(\SimpleXMLElement $element, string $attribute, ?string $value): \SimpleXMLElement
    {
        if ($value) {
            if (isset($element->attributes()->$attribute)) {
                $element->attributes()->$attribute = $value;
            } else {
                $element->addAttribute($attribute, $value);
            }
        }

        return $element;
    }
    protected static function getImageService()
    {
        return GeneralUtility::makeInstance(ImageService::class);
    }
}
