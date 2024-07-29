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
const {PanelBody, CheckboxControl, Spinner} = wp.components;
const {serverSideRender: ServerSideRender} = wp;
const {useState, useEffect} = wp.element;
const {__} = wp.i18n;

const blockMainCssClass = 'api-data-block';

registerBlockType(metadata, {
  edit: (props) => {
    const {attributes, className, setAttributes} = props;

    const blockProps = useBlockProps({
      className: [blockMainCssClass, className],
    });

    const [APIData, setAPIData] = useState([]);

    useEffect(() => {

      wp.apiFetch({path: '/abp/v1/api-data'})
        .then(response => {
          if (response.code === 'success' && response.data) {
            setAPIData(response.data);
          } else if (response.message) {
            setAPIData(response.message);
          } else {
            // eslint-disable-next-line no-console
            console.error(__('Error fetching API data', 'api-based-plugin'));
          }
        })
        .catch(error => {
          // eslint-disable-next-line no-console
          console.error(__('Error fetching API data: ', 'api-based-plugin'), error);
        });

    }, [attributes.testParam]); // The empty dependency array ensures this effect runs only once when the component mounts

    const renderControls = (
      <InspectorControls key="API Data Settings">
        <PanelBody title={__('API Data Settings', 'api-based-plugin')}>
          <CheckboxControl
            label={__('Test Param', 'api-based-plugin')}
            checked={attributes.testParam}
            onChange={(testParam) => setAttributes({testParam})}
          />
        </PanelBody>
      </InspectorControls>
    );

    const renderOutput = (
      <div {...blockProps}>
        {(APIData && attributes.testParam) ? (
          <div dangerouslySetInnerHTML={{__html: APIData}}/>
        ) : (
          <>
            <Spinner key="siteSpinner"/>
            <ServerSideRender
              block={metadata.name}
              attributes={attributes}
            />
          </>
        )}
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
