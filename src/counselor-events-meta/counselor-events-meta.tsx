import { DateTimePicker, Panel, PanelBody, PanelRow, TextControl } from '@wordpress/components';
import * as React from 'react';

export const CounselorEventsMeta = ( { fields, updateFields }: MetaFormProps ) => (
	<div>
		<Panel header="Event Info">
			<PanelBody>
				<TextControl
					label="Location"
					name="location"
					value={fields.location}
					onChange={( newValue: string ) => {
						updateFields( { location: newValue } );
					}}
				/>
			</PanelBody>
			<PanelBody>
				<div>
					<h2>Start Time</h2>
					<DateTimePicker
						currentDate={fields.start_time}
						onChange={( newTime: string ) => updateFields( { start_time: newTime } )}
						is12Hour={true}
					/>
					<input type="hidden" name="start_time" value={fields.start_time} />
				</div>
			</PanelBody>
		</Panel>
	</div>
);
