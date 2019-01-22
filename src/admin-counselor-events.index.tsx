import { withState } from '@wordpress/compose';
import domReady from '@wordpress/dom-ready';
import { render } from '@wordpress/element';
import * as React from 'react';

import { CounselorEventsMeta } from './counselor-events-meta';

const { FIELDS } = colbyCounselorsBackend;

const CounselorEventsMetaWithState = withState( {
	fields: FIELDS,
} )( ( { fields, setState }: { fields: MetaFormFields; setState: ( {} ) => void } ) => (
	<CounselorEventsMeta
		fields={fields}
		updateFields={( updates: {} ) => {
			setState( { fields: { ...fields, ...updates } } );
		}}
	/>
) );

domReady( () => {
	const root = document.querySelector( '[data-counselor-events-root]' );

	if ( root ) {
		render( <CounselorEventsMetaWithState />, root );
	}
} );
