<?php

declare(strict_types=1);

namespace Bitrix\Bizproc\Internal\Entity\Activity\Interface;

interface IBPMultipleInputActivity
{
	public function getInputPortIds(): array;
}
