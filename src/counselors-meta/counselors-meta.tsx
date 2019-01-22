import { CheckboxControl, Panel, PanelBody, TextControl } from '@wordpress/components';
import * as React from 'react';

export const CounselorsMeta = ( { fields, updateFields }: MetaFormProps ) => (
	<div>
		<Panel header="Counselor Info">
			<PanelBody>
				<TextControl
					label="First Name"
					name="first_name"
					value={fields.first_name}
					onChange={( newValue: string ) => {
						updateFields( { first_name: newValue } );
					}}
				/>

				<TextControl
					label="Last Name"
					name="last_name"
					value={fields.last_name}
					onChange={( newValue: string ) => {
						updateFields( { last_name: newValue } );
					}}
				/>

				<TextControl
					label="Job Title"
					name="job_title"
					value={fields.job_title}
					onChange={( newValue: string ) => {
						updateFields( { job_title: newValue } );
					}}
				/>

				<TextControl
					label="Phone"
					name="phone"
					type="tel"
					value={fields.phone}
					required
					onChange={( newValue: string ) => {
						updateFields( { phone: newValue } );
					}}
				/>

				<TextControl
					label="Email"
					name="email"
					type="email"
					value={fields.email}
					onChange={( newValue: string ) => {
						updateFields( { email: newValue } );
					}}
				/>

				<CheckboxControl
					label="Regional representative?"
					name="regional_representative"
					checked={fields.regional_representative}
					onChange={( newValue: boolean ) => {
						updateFields( { regional_representative: newValue } );
					}}
				/>
			</PanelBody>
		</Panel>
	</div>
);
