/**
 * Block dependencies
 */
import metadata from '../block.json';

/**
 * Internal block libraries
 */
const {registerBlockType} = wp.blocks;
const {useBlockProps} = wp.blockEditor;

const {InspectorControls} = wp.blockEditor;
const {PanelBody, SelectControl} = wp.components;
const {serverSideRender: ServerSideRender} = wp;

registerBlockType(metadata, {
  edit: (props) => {
    const {attributes, className, setAttributes} = props;

    const blockProps = useBlockProps({
      className: [className],
    });

    const {columns} = attributes;

    const renderControls = (
      <InspectorControls key="API Data Settings">
        <PanelBody title="Settings" initialOpen={true}>

        </PanelBody>
      </InspectorControls>
    );

    const renderOutput = (
      <div {...blockProps} key="serverRender">
        <ServerSideRender
          block={metadata.name}
          attributes={attributes}
        />
      </div>
    );

    return [
      renderControls,
      renderOutput,
    ];
  }, // end edit
  save: () => {
    // Rendering in PHP
    return null;
  },
});
