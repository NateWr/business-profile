/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, {
/******/ 				configurable: false,
/******/ 				enumerable: true,
/******/ 				get: getter
/******/ 			});
/******/ 		}
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 0);
/******/ })
/************************************************************************/
/******/ ([
/* 0 */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(1);


/***/ }),
/* 1 */
/***/ (function(module, exports) {

var __ = wp.i18n.__;
var registerBlockType = wp.blocks.registerBlockType;
var _wp$components = wp.components,
    SelectControl = _wp$components.SelectControl,
    CheckboxControl = _wp$components.CheckboxControl,
    PanelBody = _wp$components.PanelBody,
    ServerSideRender = _wp$components.ServerSideRender,
    Disabled = _wp$components.Disabled;
var InspectorControls = wp.editor.InspectorControls;
var _bpfwp_blocks = bpfwp_blocks,
    locationOptions = _bpfwp_blocks.locationOptions;


registerBlockType('business-profile/contact-card', {
	title: __('Contact Card', 'business-profile'),
	category: 'widgets',
	icon: 'location',
	attributes: {
		location: {
			type: 'number',
			default: 0
		},
		show_name: {
			type: 'boolean',
			default: true
		},
		show_address: {
			type: 'boolean',
			default: true
		},
		show_get_directions: {
			type: 'boolean',
			default: true
		},
		show_phone: {
			type: 'boolean',
			default: true
		},
		show_contact: {
			type: 'boolean',
			default: true
		},
		show_opening_hours: {
			type: 'boolean',
			default: true
		},
		show_opening_hours_brief: {
			type: 'boolean',
			default: false
		},
		show_map: {
			type: 'boolean',
			default: true
		},
		show_image: {
			type: 'boolean',
			default: false
		}
	},
	supports: {
		html: false
	},
	edit: function edit(_ref) {
		var attributes = _ref.attributes,
		    setAttributes = _ref.setAttributes;


		return wp.element.createElement(
			'div',
			null,
			wp.element.createElement(
				InspectorControls,
				null,
				wp.element.createElement(
					PanelBody,
					null,
					locationOptions.length ? wp.element.createElement(SelectControl, {
						label: __('Select a Location', 'business-profile'),
						value: attributes.location,
						onChange: function onChange(location) {
							return setAttributes({ location: location });
						},
						options: locationOptions
					}) : '',
					wp.element.createElement(CheckboxControl, {
						label: __('Show Name', 'business-profile'),
						checked: attributes.show_name,
						onChange: function onChange(show_name) {
							setAttributes({ show_name: show_name });
						}
					}),
					wp.element.createElement(CheckboxControl, {
						label: __('Show Address', 'business-profile'),
						checked: attributes.show_address,
						onChange: function onChange(show_address) {
							setAttributes({ show_address: show_address });
						}
					}),
					wp.element.createElement(CheckboxControl, {
						label: __('Show link to get directions on Google Maps', 'business-profile'),
						checked: attributes.show_get_directions,
						onChange: function onChange(show_get_directions) {
							setAttributes({ show_get_directions: show_get_directions });
						}
					}),
					wp.element.createElement(CheckboxControl, {
						label: __('Show Phone number', 'business-profile'),
						checked: attributes.show_phone,
						onChange: function onChange(show_phone) {
							setAttributes({ show_phone: show_phone });
						}
					}),
					wp.element.createElement(CheckboxControl, {
						label: __('Show contact details', 'business-profile'),
						checked: attributes.show_contact,
						onChange: function onChange(show_contact) {
							setAttributes({ show_contact: show_contact });
						}
					}),
					wp.element.createElement(CheckboxControl, {
						label: __('Show Opening Hours', 'business-profile'),
						checked: attributes.show_opening_hours,
						onChange: function onChange(show_opening_hours) {
							setAttributes({ show_opening_hours: show_opening_hours });
						}
					}),
					wp.element.createElement(CheckboxControl, {
						label: __('Show brief opening hours on one line', 'business-profile'),
						checked: attributes.show_opening_hours_brief,
						onChange: function onChange(show_opening_hours_brief) {
							setAttributes({ show_opening_hours_brief: show_opening_hours_brief });
						}
					}),
					wp.element.createElement(CheckboxControl, {
						label: __('Show Google Map', 'business-profile'),
						checked: attributes.show_map,
						onChange: function onChange(show_map) {
							setAttributes({ show_map: show_map });
						}
					})
				)
			),
			wp.element.createElement(
				Disabled,
				null,
				wp.element.createElement(ServerSideRender, { block: 'business-profile/contact-card', attributes: attributes })
			)
		);
	},
	save: function save() {
		return null;
	}
});

/***/ })
/******/ ]);