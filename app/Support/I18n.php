<?php

namespace App\Support;

class I18n
{
	public static function yesNo(?bool $value): string
	{
		if ($value === null) {
			return '';
		}
		return $value ? trans('common.yes') : trans('common.no');
	}

	public static function onOff(bool $value): string
	{
		return $value ? trans('common.on') : trans('common.off');
	}

	public static function enabledDisabled(bool $value): string
	{
		return $value ? trans('common.enabled') : trans('common.disabled');
	}

	public static function languageName(string $locale): string
	{
		return trans("common.language_name.$locale");
	}
}
