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
				<PanelRow className="time-row">
					<style>
						{`.time-row {
                            flex-wrap: wrap;
                        }
                        .time-row > div {
                            min-width: 300px;
                        }`}
					</style>
					<div>
						<h2>Start Time</h2>
						<DateTimePicker
							currentDate={fields.start_time}
							onChange={( newTime: string ) => updateFields( { start_time: newTime } )}
							is12Hour={true}
						/>
						<input type="hidden" name="start_time" value={fields.start_time} />
					</div>
					<div>
						<h2>End Time</h2>
						<DateTimePicker
							currentDate={fields.end_time}
							onChange={( newTime: string ) => updateFields( { end_time: newTime } )}
							is12Hour={true}
						/>
						<input type="hidden" name="start_time" value={fields.end_time} />
					</div>
				</PanelRow>
			</PanelBody>
			<PanelBody>
				<TextControl
					label="Contact Name/Description"
					name="description"
					value={fields.description}
					onChange={( newValue: string ) => {
						updateFields( { description: newValue } );
					}}
				/>

				<TextControl
					label="Contact Email"
					name="email"
					type="email"
					value={fields.email}
					onChange={( newValue: string ) => {
						updateFields( { email: newValue } );
					}}
				/>

				<TextControl
					label="More Info Link"
					type="url"
					name="url"
					value={fields.url}
					onChange={( newValue: string ) => {
						updateFields( { url: newValue } );
					}}
				/>
			</PanelBody>
		</Panel>
	</div>
);
