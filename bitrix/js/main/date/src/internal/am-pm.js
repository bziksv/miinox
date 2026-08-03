import { Loc } from 'main.core';

export const AmPmMode = {
	UPPER: 1,
	LOWER: 2,
	NONE: false
};

export function isAmPmMode(returnConst: any)
{
	if (returnConst === true)
	{
		return Loc.getMessage('AMPM_MODE');
	}

	return Loc.getMessage('AMPM_MODE') !== false;
}
