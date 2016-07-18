<?php

namespace ScrumBoardItBundle\Entity\Profile;

/**
 * GeneralProfileEntity.
 */
class GeneralProfileEntity
{
    /**
     * @var string
     */
    private $oldPassword;

    /**
     * @var string
     */
    private $newPassword;

    /**
     * Set oldPassword.
     *
     * @param string $oldPassword
     *
     * @return GeneralProfileEntity
     */
    public function setOldPassword($oldPassword)
    {
        $this->oldPassword = $oldPassword;

        return $this;
    }

    /**
     * Get oldPassword.
     *
     * @return string
     */
    public function getOldPassword()
    {
        return $this->oldPassword;
    }

    /**
     * Set newPassword.
     *
     * @param string $newPassword
     *
     * @return GeneralProfileEntity
     */
    public function setNewPassword($newPassword)
    {
        $this->newPassword = $newPassword;

        return $this;
    }

    /**
     * Get newPassword.
     *
     * @return string
     */
    public function getNewPassword()
    {
        return $this->newPassword;
    }
}
