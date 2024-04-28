interface ViolationItem {
	detail: string | null;
	source: string;
	data: object;
}

export interface Violation {
	message: string;
	errors: ViolationItem[];
}
