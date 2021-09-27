/**
 * External Dependencies
 */
import assign from "lodash.assign";

/**
 * WordPress Dependencies
 */

import { __ } from "@wordpress/i18n";
import { addFilter } from "@wordpress/hooks";

import { Fragment } from "@wordpress/element";
import { InspectorControls } from "@wordpress/block-editor";
import { createHigherOrderComponent } from "@wordpress/compose";
import { TextControl, PanelBody } from "@wordpress/components";

//restrict to specific block names
const allowedBlocks = ["core/button"];

/**
 * Add custom attribute for mobile visibility.
 *
 * @param {Object} settings Settings for the block.
 *
 * @return {Object} settings Modified settings.
 */
function addAttributes(settings) {
	//check if object exists for old Gutenberg version compatibility
	if (
		typeof settings.attributes !== "undefined" &&
		allowedBlocks.includes(settings.name)
	) {
		settings.attributes = Object.assign(settings.attributes, {
			telemetryEventName: {
				type: "string",
				default: "",
			},
		});
	}

	return settings;
}

/**
 * Add mobile visibility controls on Advanced Block Panel.
 *
 * @param {function} BlockEdit Block edit component.
 *
 * @return {function} BlockEdit Modified block edit component.
 */
const withAdvancedControls = createHigherOrderComponent((BlockEdit) => {
	return (props) => {
		const { name, attributes, setAttributes, isSelected } = props;

		const { telemetryEventName } = attributes;

		console.log(props);
		return (
			<Fragment>
				<BlockEdit {...props} />

				{isSelected && allowedBlocks.includes(name) && (
					<InspectorControls>
						<PanelBody title="Everfund" initialOpen={false}>
							<div
								style={{
									marginBottom: 12,
									paddingLeft: 12,
									borderLeft: "4px #F5B000 solid",
								}}
							>
								Remember if you set a Donation link do not set a link rel
							</div>
							<TextControl
								label="Everfund Donation Link"
								help={`Your link should look like "https://evr.fund/xxxx"`}
								value={telemetryEventName}
								onChange={(value) =>
									props.setAttributes({
										telemetryEventName: value,
									})
								}
							/>
						</PanelBody>
					</InspectorControls>
				)}
			</Fragment>
		);
	};
}, "withAdvancedControls");

/**
 * Add custom element class in save element.
 *
 * @param {Object} extraProps     Block element.
 * @param {Object} blockType      Blocks object.
 * @param {Object} attributes     Blocks attributes.
 *
 * @return {Object} extraProps Modified block element.
 */
function applyExtraClass(extraProps, blockType, attributes) {
	const { telemetryEventName } = attributes;

	if (allowedBlocks.includes(blockType.name)) {
		console.log(extraProps, blockType, attributes);
	}
	//check if attribute exists for old Gutenberg version compatibility
	//add class only when visibleOnMobile = false
	if (
		typeof telemetryEventName !== "undefined" &&
		telemetryEventName !== "" &&
		allowedBlocks.includes(blockType.name)
	) {
		// extraProps.className = classnames(extraProps.className, "mobile-hidden");

		assign(extraProps, {
			"data-ef-modal": telemetryEventName,
		});
	}

	return extraProps;
}

//add filters

addFilter(
	"blocks.registerBlockType",
	"editorskit/custom-attributes",
	addAttributes
);

addFilter(
	"editor.BlockEdit",
	"editorskit/custom-advanced-control",
	withAdvancedControls
);

addFilter(
	"blocks.getSaveContent.extraProps",
	"editorskit/applyExtraClass",
	applyExtraClass
);
