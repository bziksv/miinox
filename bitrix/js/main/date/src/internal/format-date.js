import { Loc, Type } from 'main.core';
import { convertBitrixFormat } from './convert-bitrix-format';
import { isAmPmMode, AmPmMode } from './am-pm';

export function formatDate(format, timestamp, now, utc): string
{
	/*
	PHP to Javascript:
		time() = new Date()
		mktime(...) = new Date(...)
		gmmktime(...) = new Date(Date.UTC(...))
		mktime(0,0,0, 1, 1, 1970) != 0          new Date(1970,0,1).getTime() != 0
		gmmktime(0,0,0, 1, 1, 1970) == 0        new Date(Date.UTC(1970,0,1)).getTime() == 0
		date('d.m.Y H:i:s') = BX.Main.Date.format('d.m.Y H:i:s')
		gmdate('d.m.Y H:i:s') = BX.Main.Date.format('d.m.Y H:i:s', null, null, true);
	*/
	const date = Type.isDate(timestamp) ? new Date(timestamp.getTime()) : Type.isNumber(timestamp) ? new Date(timestamp * 1000) : new Date();
	const nowDate = Type.isDate(now) ? new Date(now.getTime()) : Type.isNumber(now) ? new Date(now * 1000) : new Date();
	const isUTC = !!utc;

	if (Type.isArray(format))
	{
		return _formatDateInterval(format, date, nowDate, isUTC);
	}
	else
	{
		if (!Type.isStringFilled(format))
		{
			return '';
		}
	}

	const replaceMap = (format.match(/{{([^{}]*)}}/g) || []).map((x) => {
		return (x.match(/[^{}]+/) || [''])[0];
	});
	if (replaceMap.length > 0)
	{
		replaceMap.forEach((element, index) => {
			format = format.replace('{{' + element + '}}', '{{' + index + '}}');
		});
	}

	const formatRegex = /\\?(sago|iago|isago|Hago|dago|mago|Yago|sdiff|idiff|Hdiff|ddiff|mdiff|Ydiff|sshort|ishort|Hshort|dshort|mhort|Yshort|yesterday|today|tommorow|tomorrow|[a-z])/gi;

	const dateFormats = {
		d: () => {
			// Day of the month 01 to 31
			return getDate(date).toString().padStart(2, '0');
		},

		D: () => {
			//Mon through Sun
			return Loc.getMessage('DOW_' + getDay(date));
		},

		j: () => {
			//Day of the month 1 to 31
			return getDate(date);
		},

		l: () => {
			//Sunday through Saturday
			return Loc.getMessage('DAY_OF_WEEK_' + getDay(date));
		},

		N: () => {
			//1 (for Monday) through 7 (for Sunday)
			return getDay(date) || 7;
		},

		S: () => {
			//st, nd, rd or th. Works well with j
			if (getDate(date) % 10 == 1 && getDate(date) != 11)
			{
				return 'st';
			}
			else if (getDate(date) % 10 == 2 && getDate(date) != 12)
			{
				return 'nd';
			}
			else if (getDate(date) % 10 == 3 && getDate(date) != 13)
			{
				return 'rd';
			}
			else
			{
				return 'th';
			}
		},

		w: () => {
			//0 (for Sunday) through 6 (for Saturday)
			return getDay(date);
		},

		z: () => {
			//0 through 365
			const firstDay = new Date(getFullYear(date), 0, 1);
			const currentDay = new Date(getFullYear(date), getMonth(date), getDate(date));
			return Math.ceil((currentDay - firstDay) / (24 * 3600 * 1000));
		},

		W: () => {
			//ISO-8601 week number of year
			const newDate = new Date(date.getTime());
			const dayNumber = (getDay(date) + 6) % 7;
			setDate(newDate, getDate(newDate) - dayNumber + 3);
			const firstThursday = newDate.getTime();
			setMonth(newDate, 0, 1);
			if (getDay(newDate) != 4)
			{
				setMonth(newDate, 0, 1 + ((4 - getDay(newDate)) + 7) % 7);
			}
			const weekNumber = 1 + Math.ceil((firstThursday - newDate) / (7 * 24 * 3600 * 1000));

			return weekNumber.toString().padStart(2, '0');
		},

		F: () => {
			//January through December
			return Loc.getMessage('MONTH_' + (getMonth(date) + 1) + '_S');
		},

		f: () => {
			//January through December
			return Loc.getMessage('MONTH_' + (getMonth(date) + 1));
		},

		m: () => {
			//Numeric representation of a month 01 through 12
			return (getMonth(date) + 1).toString().padStart(2, '0');
		},

		M: () => {
			//A short textual representation of a month, three letters Jan through Dec
			return Loc.getMessage('MON_' + (getMonth(date) + 1));
		},

		n: () => {
			//Numeric representation of a month 1 through 12
			return getMonth(date) + 1;
		},

		t: () => {
			//Number of days in the given month 28 through 31
			const lastMonthDay = isUTC ? new Date(Date.UTC(getFullYear(date), getMonth(date) + 1, 0)) : new Date(getFullYear(date), getMonth(date) + 1, 0);
			return getDate(lastMonthDay);
		},

		L: () => {
			//1 if it is a leap year, 0 otherwise.
			const year = getFullYear(date);
			return (year % 4 == 0 && year % 100 != 0 || year % 400 == 0 ? 1 : 0);
		},

		o: () => {
			//ISO-8601 year number
			const correctDate = new Date(date.getTime());
			setDate(correctDate, getDate(correctDate) - ((getDay(date) + 6) % 7) + 3);
			return getFullYear(correctDate);
		},

		Y: () => {
			//A full numeric representation of a year, 4 digits
			return getFullYear(date);
		},

		y: () => {
			//A two digit representation of a year
			return getFullYear(date).toString().slice(2);
		},

		a: () => {
			//am or pm
			return getHours(date) > 11 ? 'pm' : 'am';
		},

		A: () => {
			//AM or PM
			return getHours(date) > 11 ? 'PM' : 'AM';
		},

		B: () => {
			//000 through 999
			const swatch = ((date.getUTCHours() + 1) % 24) + date.getUTCMinutes() / 60 + date.getUTCSeconds() / 3600;
			return Math.floor(swatch * 1000 / 24).toString().padStart(3, '0');
		},

		g: () => {
			//12-hour format of an hour without leading zeros 1 through 12
			return getHours(date) % 12 || 12;
		},

		G: () => {
			//24-hour format of an hour without leading zeros 0 through 23
			return getHours(date);
		},

		h: () => {
			//12-hour format of an hour with leading zeros 01 through 12
			return (getHours(date) % 12 || 12).toString().padStart(2, '0');
		},

		H: () => {
			//24-hour format of an hour with leading zeros 00 through 23
			return getHours(date).toString().padStart(2, '0');
		},

		i: () => {
			//Minutes with leading zeros 00 to 59
			return getMinutes(date).toString().padStart(2, '0');
		},

		s: () => {
			//Seconds, with leading zeros 00 through 59
			return getSeconds(date).toString().padStart(2, '0');
		},

		u: () => {
			//Microseconds
			return (getMilliseconds(date) * 1000).toString().padStart(6, '0');
		},

		e: () => {
			if (isUTC)
			{
				return 'UTC';
			}
			return '';
		},

		I: () => {
			if (isUTC)
			{
				return 0;
			}

			//Whether or not the date is in daylight saving time 1 if Daylight Saving Time, 0 otherwise
			const firstJanuary = new Date(getFullYear(date), 0, 1);
			const firstJanuaryUTC = Date.UTC(getFullYear(date), 0, 1);
			const firstJuly = new Date(getFullYear(date), 6, 0);
			const firstJulyUTC = Date.UTC(getFullYear(date), 6, 0);
			return 0 + ((firstJanuary - firstJanuaryUTC) !== (firstJuly - firstJulyUTC));
		},

		O: () => {
			if (isUTC)
			{
				return '+0000';
			}

			//Difference to Greenwich time (GMT) in hours +0200
			const timezoneOffset = date.getTimezoneOffset();
			const timezoneOffsetAbs = Math.abs(timezoneOffset);
			return (timezoneOffset > 0 ? '-' : '+') + (Math.floor(timezoneOffsetAbs / 60) * 100 + timezoneOffsetAbs % 60).toString().padStart(4, '0');
		},

		P: () => {
			if (isUTC)
			{
				return '+00:00';
			}

			//Difference to Greenwich time (GMT) with colon between hours and minutes +02:00
			const difference = this.O();
			return difference.substr(0, 3) + ':' + difference.substr(3);
		},

		Z: () => {
			if (isUTC)
			{
				return 0;
			}
			//Timezone offset in seconds. The offset for timezones west of UTC is always negative,
			//and for those east of UTC is always positive.
			return -date.getTimezoneOffset() * 60;
		},

		c: () => {
			//ISO 8601 date
			return 'Y-m-d\\TH:i:sP'.replace(formatRegex, _replaceDateFormat);
		},

		r: () => {
			//RFC 2822 formatted date
			return 'D, d M Y H:i:s O'.replace(formatRegex, _replaceDateFormat);
		},

		U: () => {
			//Seconds since the Unix Epoch
			return Math.floor(date.getTime() / 1000);
		},

		sago: () => {
			return _formatDateMessage(intval((nowDate - date) / 1000), {
				'0': 'FD_SECOND_AGO_0',
				'1': 'FD_SECOND_AGO_1',
				'10_20': 'FD_SECOND_AGO_10_20',
				'MOD_1': 'FD_SECOND_AGO_MOD_1',
				'MOD_2_4': 'FD_SECOND_AGO_MOD_2_4',
				'MOD_OTHER': 'FD_SECOND_AGO_MOD_OTHER'
			});
		},

		sdiff: () => {
			return _formatDateMessage(intval((nowDate - date) / 1000), {
				'0': 'FD_SECOND_DIFF_0',
				'1': 'FD_SECOND_DIFF_1',
				'10_20': 'FD_SECOND_DIFF_10_20',
				'MOD_1': 'FD_SECOND_DIFF_MOD_1',
				'MOD_2_4': 'FD_SECOND_DIFF_MOD_2_4',
				'MOD_OTHER': 'FD_SECOND_DIFF_MOD_OTHER'
			});
		},

		sshort: () => {
			return Loc.getMessage('FD_SECOND_SHORT').replace(/#VALUE#/g, intval((nowDate - date) / 1000));
		},

		iago: () => {
			return _formatDateMessage(intval((nowDate - date) / 60 / 1000), {
				'0': 'FD_MINUTE_AGO_0',
				'1': 'FD_MINUTE_AGO_1',
				'10_20': 'FD_MINUTE_AGO_10_20',
				'MOD_1': 'FD_MINUTE_AGO_MOD_1',
				'MOD_2_4': 'FD_MINUTE_AGO_MOD_2_4',
				'MOD_OTHER': 'FD_MINUTE_AGO_MOD_OTHER'
			});
		},

		idiff: () => {
			return _formatDateMessage(intval((nowDate - date) / 60 / 1000), {
				'0': 'FD_MINUTE_DIFF_0',
				'1': 'FD_MINUTE_DIFF_1',
				'10_20': 'FD_MINUTE_DIFF_10_20',
				'MOD_1': 'FD_MINUTE_DIFF_MOD_1',
				'MOD_2_4': 'FD_MINUTE_DIFF_MOD_2_4',
				'MOD_OTHER': 'FD_MINUTE_DIFF_MOD_OTHER'
			});
		},

		isago: () => {
			const minutesAgo = intval((nowDate - date) / 60 / 1000);
			let result = _formatDateMessage(minutesAgo, {
				'0': 'FD_MINUTE_0',
				'1': 'FD_MINUTE_1',
				'10_20': 'FD_MINUTE_10_20',
				'MOD_1': 'FD_MINUTE_MOD_1',
				'MOD_2_4': 'FD_MINUTE_MOD_2_4',
				'MOD_OTHER': 'FD_MINUTE_MOD_OTHER'
			});

			result += ' ';

			const secondsAgo = intval((nowDate - date) / 1000) - (minutesAgo * 60);
			result += _formatDateMessage(secondsAgo, {
				'0': 'FD_SECOND_AGO_0',
				'1': 'FD_SECOND_AGO_1',
				'10_20': 'FD_SECOND_AGO_10_20',
				'MOD_1': 'FD_SECOND_AGO_MOD_1',
				'MOD_2_4': 'FD_SECOND_AGO_MOD_2_4',
				'MOD_OTHER': 'FD_SECOND_AGO_MOD_OTHER'
			});
			return result;
		},

		ishort: () => {
			return Loc.getMessage('FD_MINUTE_SHORT').replace(/#VALUE#/g, intval((nowDate - date) / 60 / 1000));
		},

		Hago: () => {
			return _formatDateMessage(intval((nowDate - date) / 60 / 60 / 1000), {
				'0': 'FD_HOUR_AGO_0',
				'1': 'FD_HOUR_AGO_1',
				'10_20': 'FD_HOUR_AGO_10_20',
				'MOD_1': 'FD_HOUR_AGO_MOD_1',
				'MOD_2_4': 'FD_HOUR_AGO_MOD_2_4',
				'MOD_OTHER': 'FD_HOUR_AGO_MOD_OTHER'
			});
		},

		Hdiff: () => {
			return _formatDateMessage(intval((nowDate - date) / 60 / 60 / 1000), {
				'0': 'FD_HOUR_DIFF_0',
				'1': 'FD_HOUR_DIFF_1',
				'10_20': 'FD_HOUR_DIFF_10_20',
				'MOD_1': 'FD_HOUR_DIFF_MOD_1',
				'MOD_2_4': 'FD_HOUR_DIFF_MOD_2_4',
				'MOD_OTHER': 'FD_HOUR_DIFF_MOD_OTHER'
			});
		},

		Hshort: () => {
			return Loc.getMessage('FD_HOUR_SHORT').replace(/#VALUE#/g, intval((nowDate - date) / 60 / 60 / 1000));
		},

		yesterday: () => {
			return Loc.getMessage('FD_YESTERDAY');
		},

		today: () => {
			return Loc.getMessage('FD_TODAY');
		},

		tommorow: () => {
			return Loc.getMessage('FD_TOMORROW');
		},

		tomorrow: () => {
			return Loc.getMessage('FD_TOMORROW');
		},

		dago: () => {
			return _formatDateMessage(intval((nowDate - date) / 60 / 60 / 24 / 1000), {
				'0': 'FD_DAY_AGO_0',
				'1': 'FD_DAY_AGO_1',
				'10_20': 'FD_DAY_AGO_10_20',
				'MOD_1': 'FD_DAY_AGO_MOD_1',
				'MOD_2_4': 'FD_DAY_AGO_MOD_2_4',
				'MOD_OTHER': 'FD_DAY_AGO_MOD_OTHER'
			});
		},

		ddiff: () => {
			return _formatDateMessage(intval((nowDate - date) / 60 / 60 / 24 / 1000), {
				'0': 'FD_DAY_DIFF_0',
				'1': 'FD_DAY_DIFF_1',
				'10_20': 'FD_DAY_DIFF_10_20',
				'MOD_1': 'FD_DAY_DIFF_MOD_1',
				'MOD_2_4': 'FD_DAY_DIFF_MOD_2_4',
				'MOD_OTHER': 'FD_DAY_DIFF_MOD_OTHER'
			});
		},

		dshort: () => {
			return Loc.getMessage('FD_DAY_SHORT').replace(/#VALUE#/g, intval((nowDate - date) / 60 / 60 / 24 / 1000));
		},

		mago: () => {
			return _formatDateMessage(intval((nowDate - date) / 60 / 60 / 24 / 31 / 1000), {
				'0': 'FD_MONTH_AGO_0',
				'1': 'FD_MONTH_AGO_1',
				'10_20': 'FD_MONTH_AGO_10_20',
				'MOD_1': 'FD_MONTH_AGO_MOD_1',
				'MOD_2_4': 'FD_MONTH_AGO_MOD_2_4',
				'MOD_OTHER': 'FD_MONTH_AGO_MOD_OTHER'
			});
		},

		mdiff: () => {
			return _formatDateMessage(intval((nowDate - date) / 60 / 60 / 24 / 31 / 1000), {
				'0': 'FD_MONTH_DIFF_0',
				'1': 'FD_MONTH_DIFF_1',
				'10_20': 'FD_MONTH_DIFF_10_20',
				'MOD_1': 'FD_MONTH_DIFF_MOD_1',
				'MOD_2_4': 'FD_MONTH_DIFF_MOD_2_4',
				'MOD_OTHER': 'FD_MONTH_DIFF_MOD_OTHER'
			});
		},

		mshort: () => {
			return Loc.getMessage('FD_MONTH_SHORT').replace(/#VALUE#/g, intval((nowDate - date) / 60 / 60 / 24 / 31 / 1000));
		},

		Yago: () => {
			return _formatDateMessage(intval((nowDate - date) / 60 / 60 / 24 / 365 / 1000), {
				'0': 'FD_YEARS_AGO_0',
				'1': 'FD_YEARS_AGO_1',
				'10_20': 'FD_YEARS_AGO_10_20',
				'MOD_1': 'FD_YEARS_AGO_MOD_1',
				'MOD_2_4': 'FD_YEARS_AGO_MOD_2_4',
				'MOD_OTHER': 'FD_YEARS_AGO_MOD_OTHER'
			});
		},

		Ydiff: () => {
			return _formatDateMessage(intval((nowDate - date) / 60 / 60 / 24 / 365 / 1000), {
				'0': 'FD_YEARS_DIFF_0',
				'1': 'FD_YEARS_DIFF_1',
				'10_20': 'FD_YEARS_DIFF_10_20',
				'MOD_1': 'FD_YEARS_DIFF_MOD_1',
				'MOD_2_4': 'FD_YEARS_DIFF_MOD_2_4',
				'MOD_OTHER': 'FD_YEARS_DIFF_MOD_OTHER'
			});
		},

		Yshort: () => {
			return _formatDateMessage(intval((nowDate - date) / 60 / 60 / 24 / 365 / 1000), {
				'0': 'FD_YEARS_SHORT_0',
				'1': 'FD_YEARS_SHORT_1',
				'10_20': 'FD_YEARS_SHORT_10_20',
				'MOD_1': 'FD_YEARS_SHORT_MOD_1',
				'MOD_2_4': 'FD_YEARS_SHORT_MOD_2_4',
				'MOD_OTHER': 'FD_YEARS_SHORT_MOD_OTHER'
			});
		},

		x: () => {
			const ampm = isAmPmMode(true);
			const timeFormat = (ampm === AmPmMode.LOWER ? 'g:i a' : (ampm === AmPmMode.UPPER ? 'g:i A' : 'H:i'));

			return formatDate([
				['tomorrow', 'tomorrow, ' + timeFormat],
				['-', convertBitrixFormat(Loc.getMessage('FORMAT_DATETIME')).replace(/:s/g, '')],
				['s', 'sago'],
				['i', 'iago'],
				['today', 'today, ' + timeFormat],
				['yesterday', 'yesterday, ' + timeFormat],
				['', convertBitrixFormat(Loc.getMessage('FORMAT_DATETIME')).replace(/:s/g, '')]
			], date, nowDate, isUTC);
		},

		X: () => {

			const ampm = isAmPmMode(true);
			const timeFormat = (ampm === AmPmMode.LOWER ? 'g:i a' : (ampm === AmPmMode.UPPER ? 'g:i A' : 'H:i'));

			const day = formatDate([
				['tomorrow', 'tomorrow'],
				['-', convertBitrixFormat(Loc.getMessage('FORMAT_DATE'))],
				['today', 'today'],
				['yesterday', 'yesterday'],
				['', convertBitrixFormat(Loc.getMessage('FORMAT_DATE'))]
			], date, nowDate, isUTC);

			const time = formatDate([
				['tomorrow', timeFormat],
				['today', timeFormat],
				['yesterday', timeFormat],
				['', '']
			], date, nowDate, isUTC);

			if (time.length > 0)
			{
				return Loc.getMessage('FD_DAY_AT_TIME').replace(/#DAY#/g, day).replace(/#TIME#/g, time);
			}
			else
			{
				return day;
			}
		},

		Q: () => {
			const daysAgo = intval((nowDate - date) / 60 / 60 / 24 / 1000);
			if (daysAgo == 0)
			{
				return Loc.getMessage('FD_DAY_DIFF_1').replace(/#VALUE#/g, 1);
			}
			else
			{
				return formatDate([['d', 'ddiff'], ['m', 'mdiff'], ['', 'Ydiff']], date, nowDate);
			}
		}
	};

	let cutZeroTime = false;
	if (format[0] && format[0] == '^')
	{
		cutZeroTime = true;
		format = format.substr(1);
	}

	let result = format.replace(formatRegex, _replaceDateFormat);

	if (cutZeroTime)
	{
		/* 	15.04.12 13:00:00 => 15.04.12 13:00
			00:01:00 => 00:01
			4 may 00:00:00 => 4 may
			01-01-12 00:00 => 01-01-12
		*/

		result = result.replace(/\s*00:00:00\s*/g, '').replace(/(\d\d:\d\d)(:00)/g, '$1').replace(/(\s*00:00\s*)(?!:)/g, '');
	}

	if (replaceMap.length > 0)
	{
		replaceMap.forEach(function(element, index)
		{
			result = result.replace('{{' + index + '}}', element);
		});
	}

	return result;

	function _formatDateInterval(formats, date, nowDate, isUTC)
	{
		const secondsAgo = intval((nowDate - date) / 1000);
		for (let i = 0; i < formats.length; i++)
		{
			const formatInterval = formats[i][0];
			const formatValue = formats[i][1];
			let match = null;
			if (formatInterval == 's')
			{
				if (secondsAgo < 60)
				{
					return formatDate(formatValue, date, nowDate, isUTC);
				}
			}
			else if ((match = /^s(\d+)\>?(\d+)?/.exec(formatInterval)) != null)
			{
				if (match[1] && match[2])
				{
					if (
						secondsAgo < match[1]
						&& secondsAgo > match[2]
					)
					{
						return formatDate(formatValue, date, nowDate, isUTC);
					}
				}
				else if (secondsAgo < match[1])
				{
					return formatDate(formatValue, date, nowDate, isUTC);
				}
			}
			else if (formatInterval == 'i')
			{
				if (secondsAgo < 60 * 60)
				{
					return formatDate(formatValue, date, nowDate, isUTC);
				}
			}
			else if ((match = /^i(\d+)\>?(\d+)?/.exec(formatInterval)) != null)
			{
				if (match[1] && match[2])
				{
					if (
						secondsAgo < match[1] * 60
						&& secondsAgo > match[2] * 60
					)
					{
						return formatDate(formatValue, date, nowDate, isUTC);
					}
				}
				else if (secondsAgo < match[1] * 60)
				{
					return formatDate(formatValue, date, nowDate, isUTC);
				}
			}
			else if (formatInterval == 'H')
			{
				if (secondsAgo < 24 * 60 * 60)
				{
					return formatDate(formatValue, date, nowDate, isUTC);
				}
			}
			else if ((match = /^H(\d+)\>?(\d+)?/.exec(formatInterval)) != null)
			{
				if (match[1] && match[2])
				{
					if (
						secondsAgo < match[1] * 60 * 60
						&& secondsAgo > match[2] * 60 * 60
					)
					{
						return formatDate(formatValue, date, nowDate, isUTC);
					}
				}
				else if (secondsAgo < match[1] * 60 * 60)
				{
					return formatDate(formatValue, date, nowDate, isUTC);
				}
			}
			else if (formatInterval == 'd')
			{
				if (secondsAgo < 31 * 24 * 60 * 60)
				{
					return formatDate(formatValue, date, nowDate, isUTC);
				}
			}
			else if ((match = /^d(\d+)\>?(\d+)?/.exec(formatInterval)) != null)
			{
				if (match[1] && match[2])
				{
					if (
						secondsAgo < match[1] * 24 * 60 * 60
						&& secondsAgo > match[2] * 24 * 60 * 60
					)
					{
						return formatDate(formatValue, date, nowDate, isUTC);
					}
				}
				else if (secondsAgo < match[1] * 24 * 60 * 60)
				{
					return formatDate(formatValue, date, nowDate, isUTC);
				}
			}
			else if (formatInterval == 'm')
			{
				if (secondsAgo < 365 * 24 * 60 * 60)
				{
					return formatDate(formatValue, date, nowDate, isUTC);
				}
			}
			else if ((match = /^m(\d+)\>?(\d+)?/.exec(formatInterval)) != null)
			{
				if (match[1] && match[2])
				{
					if (
						secondsAgo < match[1] * 31 * 24 * 60 * 60
						&& secondsAgo > match[2] * 31 * 24 * 60 * 60
					)
					{
						return formatDate(formatValue, date, nowDate, isUTC);
					}
				}
				else if (secondsAgo < match[1] * 31 * 24 * 60 * 60)
				{
					return formatDate(formatValue, date, nowDate, isUTC);
				}
			}
			else if (formatInterval == 'now')
			{
				if (date.getTime() == nowDate.getTime())
				{
					return formatDate(formatValue, date, nowDate, isUTC);
				}
			}
			else if (formatInterval == 'today')
			{
				const year = getFullYear(nowDate);
				const month = getMonth(nowDate);
				const day = getDate(nowDate);
				const todayStart = isUTC ? new Date(Date.UTC(year, month, day, 0, 0, 0, 0)) : new Date(year, month, day, 0, 0, 0, 0);
				const todayEnd = isUTC ? new Date(Date.UTC(year, month, day + 1, 0, 0, 0, 0)) : new Date(year, month, day + 1, 0, 0, 0, 0);
				if (date >= todayStart && date < todayEnd)
				{
					return formatDate(formatValue, date, nowDate, isUTC);
				}
			}
			else if (formatInterval == 'todayFuture')
			{
				const year = getFullYear(nowDate);
				const month = getMonth(nowDate);
				const day = getDate(nowDate);
				const todayStart = nowDate.getTime();
				const todayEnd = isUTC ? new Date(Date.UTC(year, month, day + 1, 0, 0, 0, 0)) : new Date(year, month, day + 1, 0, 0, 0, 0);
				if (date >= todayStart && date < todayEnd)
				{
					return formatDate(formatValue, date, nowDate, isUTC);
				}
			}
			else if (formatInterval == 'yesterday')
			{
				const year = getFullYear(nowDate);
				const month = getMonth(nowDate);
				const day = getDate(nowDate);
				const yesterdayStart = isUTC ? new Date(Date.UTC(year, month, day - 1, 0, 0, 0, 0)) : new Date(year, month, day - 1, 0, 0, 0, 0);
				const yesterdayEnd = isUTC ? new Date(Date.UTC(year, month, day, 0, 0, 0, 0)) : new Date(year, month, day, 0, 0, 0, 0);
				if (date >= yesterdayStart && date < yesterdayEnd)
				{
					return formatDate(formatValue, date, nowDate, isUTC);
				}
			}
			else if (formatInterval == 'tommorow' || formatInterval == 'tomorrow')
			{
				const year = getFullYear(nowDate);
				const month = getMonth(nowDate);
				const day = getDate(nowDate);
				const tomorrowStart = isUTC ? new Date(Date.UTC(year, month, day + 1, 0, 0, 0, 0)) : new Date(year, month, day + 1, 0, 0, 0, 0);
				const tomorrowEnd = isUTC ? new Date(Date.UTC(year, month, day + 2, 0, 0, 0, 0)) : new Date(year, month, day + 2, 0, 0, 0, 0);
				if (date >= tomorrowStart && date < tomorrowEnd)
				{
					return formatDate(formatValue, date, nowDate, isUTC);
				}
			}
			else if (formatInterval == '-')
			{
				if (secondsAgo < 0)
				{
					return formatDate(formatValue, date, nowDate, isUTC);
				}
			}
		}

		//return formats.length > 0 ? formatDate(formats.pop()[1], date, nowDate, isUTC) : '';
		return formats.length > 0 ? formatDate(formats[formats.length - 1][1], date, nowDate, isUTC) : '';
	}

	function getFullYear(date)
	{
		return isUTC ? date.getUTCFullYear() : date.getFullYear();
	}

	function getDate(date)
	{
		return isUTC ? date.getUTCDate() : date.getDate();
	}

	function getMonth(date)
	{
		return isUTC ? date.getUTCMonth() : date.getMonth();
	}

	function getHours(date)
	{
		return isUTC ? date.getUTCHours() : date.getHours();
	}

	function getMinutes(date)
	{
		return isUTC ? date.getUTCMinutes() : date.getMinutes();
	}

	function getSeconds(date)
	{
		return isUTC ? date.getUTCSeconds() : date.getSeconds();
	}

	function getMilliseconds(date)
	{
		return isUTC ? date.getUTCMilliseconds() : date.getMilliseconds();
	}

	function getDay(date)
	{
		return isUTC ? date.getUTCDay() : date.getDay();
	}

	function setDate(date, dayValue)
	{
		return isUTC ? date.setUTCDate(dayValue) : date.setDate(dayValue);
	}

	function setMonth(date, monthValue, dayValue)
	{
		return isUTC ? date.setUTCMonth(monthValue, dayValue) : date.setMonth(monthValue, dayValue);
	}

	function _formatDateMessage(value, messages)
	{
		const val = value < 100 ? Math.abs(value) : Math.abs(value % 100);
		const dec = val % 10;
		let message = '';

		if (val == 0)
		{
			message = Loc.getMessage(messages['0']);
		}
		else if (val == 1)
		{
			message = Loc.getMessage(messages['1']);
		}
		else if (val >= 10 && val <= 20)
		{
			message = Loc.getMessage(messages['10_20']);
		}
		else if (dec == 1)
		{
			message = Loc.getMessage(messages['MOD_1']);
		}
		else if (2 <= dec && dec <= 4)
		{
			message = Loc.getMessage(messages['MOD_2_4']);
		}
		else
		{
			message = Loc.getMessage(messages['MOD_OTHER']);
		}

		return message.replace(/#VALUE#/g, value);
	}

	function _replaceDateFormat(match, matchFull)
	{
		if (dateFormats[match])
		{
			return dateFormats[match]();
		}
		else
		{
			return matchFull;
		}
	}

	function intval(number)
	{
		return number >= 0 ? Math.floor(number) : Math.ceil(number);
	}
}
