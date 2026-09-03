<?php

declare(strict_types=1);

use Bitrix\Bizproc\Public\Entity\Template\NodesInstaller;
use Bitrix\Main\Config\Option;

return new class extends NodesInstaller
{
	public function getModifiedTime(): int
	{
		return 1761992035;
	}

	public function shouldInstall(): bool
	{
		return (Option::get('bizproc', 'ai_coach_with_rag', 'N') === 'Y');
	}
};
