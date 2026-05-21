import TomSelect from "tom-select";

export interface TomInput extends HTMLElement {
    tomselect?: TomSelect;
    disabled: boolean;
    readOnly?: boolean;
    required: boolean;
    value: string;
    type: string;
    validity: ValidityState;
}

// Each option object in TomSelect
export type TomOption = {
    value: string;
    text: string;
    [key: string]: any;
};
