import { registerBlockType } from '@wordpress/blocks';

import edit from './edit';
import metadata from './block.json';
import "./style.css";


registerBlockType(metadata.name, {
    edit,
});
