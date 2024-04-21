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
    const [headers, setTableHeaders] = useState([]);

    useEffect(() => {
      wp.apiFetch({path: '/abp/v1/get-table-headers'})
        .then(fetchedTableHeaders => {
          setTableHeaders(fetchedTableHeaders);
        })
        .catch(error => {
          // eslint-disable-next-line no-console
          console.error(__('Error fetching table headers: ', 'api-based-plugin'), error);
        });
    }, []); // The empty dependency array ensures this effect runs only once when the component mounts

    useEffect(() => {

      const columnsData = btoa(JSON.stringify(attributes.columns || [])); // Ensure columns attribute exists and is used
      const encodedColumnsData = encodeURIComponent(columnsData);

      wp.apiFetch({path: '/abp/v1/api-data?columns=' + encodedColumnsData})
        .then(response => {
          if (response.code === 'success' && response.data) {
            setAPIData(response.data);
          }
        })
        .catch(error => {
          // eslint-disable-next-line no-console
          console.error(__('Error fetching API data: ', 'api-based-plugin'), error);
        });

    }, [attributes.columns]);

    const renderControls = (
      <InspectorControls key="API Data Settings">
        <PanelBody title={__('Headers', 'api-based-plugin')} initialOpen={true}>
          {headers.length < 1
            ? <Spinner key="siteSpinner"/>
            : (
              <>
                {headers.map((header, index) => (
                  <CheckboxControl
                    label={header}
                    checked={attributes.columns.includes(index)}
                    onChange={(isChecked) => {
                      let updatedColumns;
                      if (isChecked) {
                        updatedColumns = [...attributes.columns, index];
                      } else {
                        updatedColumns = attributes.columns.filter(item => item !== index);
                      }
                      setAttributes({columns: updatedColumns});
                    }}
                    key={`${header}-${index}`}
                  />
                ))}
              </>
            )
          }
        </PanelBody>
      </InspectorControls>
    );

    const renderOutput = (
      <div {...blockProps} dangerouslySetInnerHTML={{__html: APIData}}>
      </div>
    );

    return [
      renderControls,
      renderOutput,
    ];
  }, // end edit
  save: (props) => {

    const {attributes} = props;
    const columnsData = btoa(JSON.stringify(attributes.columns || [])); // Ensure columns attribute exists and is used

    return (
      <div
        className={blockMainCssClass}
        data-columns={columnsData}
      >
      </div>
    );
  },
});
