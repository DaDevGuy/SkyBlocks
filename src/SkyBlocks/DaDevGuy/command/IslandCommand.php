<?php
declare(strict_types=1);

namespace Skyblocks\DaDevGuy\command;


use Skyblocks\DaDevGuy\island\RankIds;
use Skyblocks\DaDevGuy\session\Session;
use Skyblocks\DaDevGuy\utils\message\MessageContainer;

abstract class IslandCommand {

    public function getAliases(): array {
        return [];
    }

    public function checkIsland(Session $session): bool {
        if($session->hasIsland()) {
            return false;
        }
        $session->sendTranslatedMessage(new MessageContainer("NEED_ISLAND"));
        return true;
    }

    public function checkFounder(Session $session): bool {
        if($this->checkIsland($session)) {
            return true;
        } elseif($session->getRank() == RankIds::FOUNDER) {
            return false;
        }
        $session->sendTranslatedMessage(new MessageContainer("MUST_BE_FOUNDER"));
        return true;
    }

    public function checkLeader(Session $session): bool {
        if($this->checkIsland($session)) {
            return true;
        } elseif($session->getRank() == RankIds::FOUNDER or $session->getRank() == RankIds::LEADER) {
            return false;
        }
        $session->sendTranslatedMessage(new MessageContainer("MUST_BE_LEADER"));
        return true;
    }

    public function checkOfficer(Session $session): bool {
        if($this->checkIsland($session)) {
            return true;
        } elseif($session->getRank() != RankIds::MEMBER) {
            return false;
        }
        $session->sendTranslatedMessage(new MessageContainer("MUST_BE_OFFICER"));
        return true;
    }

    public function checkClone(?Session $session, ?Session $ySession): bool {
        if($session === $ySession) {
            $session->sendTranslatedMessage(new MessageContainer("CANT_BE_YOURSELF"));
            return true;
        }
        return false;
    }

    public abstract function getName(): string;

    public abstract function getUsageMessageContainer(): MessageContainer;

    public abstract function getDescriptionMessageContainer(): MessageContainer;

    public abstract function onCommand(Session $session, array $args): void;

}