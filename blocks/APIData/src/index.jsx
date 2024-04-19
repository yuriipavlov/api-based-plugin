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

registerBlockType(metadata, {
  edit: (props) => {
    const {attributes, className, setAttributes} = props;

    const blockProps = useBlockProps({
      className: [className],
    });

    const [headers, setTableHeaders] = useState([]);

    useEffect(() => {
      wp.apiFetch({path: '/abp/v1/get-table-headers'})
        .then(fetchedTableHeaders => {
          setTableHeaders(fetchedTableHeaders);
        })
        .catch(error => {
          // eslint-disable-next-line no-console
          console.error('Error fetching table headers: ', error);
        });
    }, []); // The empty dependency array ensures this effect runs only once when the component mounts

    const renderControls = (
      <InspectorControls key="API Data Settings">
        <PanelBody title="Headers" initialOpen={true}>
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
