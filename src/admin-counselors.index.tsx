import { withState } from '@wordpress/compose';
import domReady from '@wordpress/dom-ready';
import { render } from '@wordpress/element';
import * as React from 'react';

import { CounselorsMeta } from './counselors-meta';

const { FIELDS } = colbyCounselorsBackend;

const CounselorsMetaWithState = withState( {
	fields: FIELDS,
} )( ( { fields, setState }: { fields: MetaFormFields; setState: ( {} ) => void } ) => (
	<CounselorsMeta
		fields={fields}
		updateFields={( updates: {} ) => {
			setState( { fields: { ...fields, ...updates } } );
		}}
	/>
) );

domReady( () => {
	const root = document.querySelector( '[data-counselors-root]' );

	if ( root ) {
		render( <CounselorsMetaWithState />, root );
	}
} );
