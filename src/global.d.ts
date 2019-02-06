declare module '@wordpress/dom-ready';

declare module '@wordpress/compose' {
	const withState: (arg0: {}) => Function;
}

declare module '@wordpress/element' {
	const render: (arg0: JSX.Element, arg1: Element) => void;
	export const Fragment: React.FunctionComponent<{ [index: string]: any }>;
}

declare module '@wordpress/components' {
	export const TextControl: React.FunctionComponent<{
		label: string;
		name: string;
		value: string;
		onChange: (arg0: string) => void;
		type?: string;
		required?: boolean;
	}>;

	export const CheckboxControl: React.FunctionComponent<{
		label: string;
		name: string;
		checked: boolean;
		onChange: (arg0: boolean) => void;
	}>;

	export const DateTimePicker: React.FunctionComponent<{
		currentDate: string;
		onChange: (arg0: string) => void;
		is12Hour: boolean;
	}>;

	export const PanelBody: React.FunctionComponent<{
		children: any;
		title?: string;
	}>;

	export const Panel: React.FunctionComponent<{
		children: any;
		header: string;
	}>;

	export const PanelRow: React.FunctionComponent<{
		children: any;
		className: string;
	}>;

	export const TabPanel: React.FunctionComponent<{
		activeClass: string;
		tabs: { name: string; title: string; className: string }[];
		children: (arg0: any) => any;
	}>;
}

declare let colbyCounselorsBackend: { FIELDS: MetaFormFields };

declare interface MetaFormFields {
	[index: string]: any;
}

declare interface MetaFormProps {
	fields: MetaFormFields;
	updateFields: ({}) => void;
}
