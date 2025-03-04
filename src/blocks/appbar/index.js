import { registerBlockType } from '@wordpress/blocks';
import "./style.css";

import edit from './edit';
import metadata from './block.json';

registerBlockType(metadata.name, {
    edit,
});
