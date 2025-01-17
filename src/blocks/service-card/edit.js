//@ts-check

import { useBlockProps } from '@wordpress/block-editor';
import { TextControl, TextareaControl } from '@wordpress/components';

/**
 * @argument {import('@wordpress/blocks').BlockEditProps<
 * 	{
 * 		desktopName: string,
 *      mobileName: string,
 * 		description: string,
 * 		url: string,
 * 		style?: {
 *          background: {
 *              backgroundImage: {
 *                  url: string,
 *              },
 *              backgroundSize: string,
 *          }
 *  	}
 *	}>} props
 * @return {import('react').ReactElement} Element to render.
 */
export default function Edit({ attributes, setAttributes, isSelected }) {
    const { desktopName, mobileName, description, url, style } = attributes;
    const backgroundImage = style?.background.backgroundImage.url;
    const backgroundSize = style?.background.backgroundSize;

    // remove background image from component style
    const props = useBlockProps()
    const customStyle = {
        ...props.style,
        backgroundImage: null,
        backgroundSize: null,
    }

    /**
     * @param {string} value The new service title for desktop screens
     */
    const updateDesktopName = (value) => {
        setAttributes({ desktopName: value })
    }

    /**
     * @param {string} value The new service title for smartphone screens
     */
    const updateMobileName = (value) => {
        setAttributes({ mobileName: value })
    }

    /**
     * @param {string} value The new service description
     */
    const updateDescription = (value) => {
        setAttributes({ description: value })
    }

    return (
        <div {...useBlockProps({ style: customStyle, className: "evolutio-service-card" })}>
            <img className="evolutio-service-card__image" src={backgroundImage} style={{ backgroundSize }} />
            <div className="evolutio-service-card__desktop-content">
                <DesktopServiceName desktopName={desktopName} updateName={updateDesktopName} isSelected={isSelected} />
                <ServiceDescription description={description} updateDescription={updateDescription} isSelected={isSelected} />
                <ReadMoreLink url={url} />
            </div>
            <div className="evolutio-service-card__mobile-content">
                <MobileServiceName mobileName={mobileName || desktopName} updateName={updateMobileName} isSelected={isSelected} />
                <ReadMoreLink url={url} mobileVersion />
            </div>
        </div>
    )
}

/**
 * @param {{desktopName: string, updateName: (desktopName: string) => void, isSelected: boolean}} props
 */
function DesktopServiceName({ desktopName, updateName, isSelected }) {
    if (isSelected) {
        return (
            <TextControl
                __nextHasNoMarginBottom
                className="evolutio-service-card__name"
                label="Service name (long version)"
                placeholder="Service name (long version)"
                hideLabelFromVision
                value={desktopName}
                onChange={updateName}
            />
        )
    }
    return <div className="evolutio-service-card__name">{desktopName}</div>
}

/**
 * @param {{mobileName: string, updateName: (mobileName: string) => void, isSelected: boolean}} props
 */
function MobileServiceName({ mobileName, updateName, isSelected }) {
    if (isSelected) {
        return (
            <TextControl
                __nextHasNoMarginBottom
                className="evolutio-service-card__name"
                label="Service name (short version)"
                placeholder="Service name (short version)"
                hideLabelFromVision
                value={mobileName}
                onChange={updateName}
            />
        )
    }
    return <div className="evolutio-service-card__name">{mobileName}</div>
}

/**
 * @param {{description: string, updateDescription: (description: string) => void, isSelected: boolean}} props
 */
function ServiceDescription({ description, updateDescription, isSelected }) {
    if (isSelected) {
        return (
            <TextareaControl
                __nextHasNoMarginBottom
                className="evolutio-service-card__description"
                label="Service description"
                placeholder="Service description"
                hideLabelFromVision
                value={description}
                onChange={updateDescription}
            />
        )
    }
    return <div className="evolutio-service-card__description">{description}</div>
}

/**
 * @param {{url: string, mobileVersion?: boolean}} props
 */
function ReadMoreLink({ url, mobileVersion }) {
    const label = mobileVersion ? "Découvrir" : "En savoir plus";
    return (
        <div className="evolutio-service-card__readmore" title={url}>
            {label}
            <svg width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M9.41016 19.9201L15.9302 13.4001C16.7002 12.6301 16.7002 11.3701 15.9302 10.6001L9.41016 4.08008" stroke="#F5A524" strokeWidth="2" strokeMiterlimit="10" strokeLinecap="round" strokeLinejoin="round" />
            </svg>
        </div>
    )
}