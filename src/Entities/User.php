<?php

namespace pandaac\OAuth2OtLand\Entities;

use League\OAuth2\Client\Provider\ResourceOwnerInterface;

class User implements ResourceOwnerInterface
{
    /**
     * Holds the user ID.
     *
     * @var integer
     */
    protected $id;

    /**
     * Holds the username.
     *
     * @var string
     */
    protected $username;

    /**
     * Holds the user title.
     *
     * @var string
     */
    protected $title;

    /**
     * Holds the user email.
     *
     * @var string
     */
    protected $email;

    /**
     * Holds the user profile link.
     *
     * @var string
     */
    protected $link;

    /**
     * Holds the user avatar.
     *
     * @var string
     */
    protected $avatar;

    /**
     */
    public function __construct(array $attributes = [])
    {
        $this->id = $attributes['user']['user_id'];
        $this->username = $attributes['user']['username'];
        $this->title = $attributes['user']['user_title'];
        $this->email = $attributes['user']['user_email'];
        $this->link = $attributes['user']['links']['permalink'];
        $this->avatar = $attributes['user']['links']['avatar'];
    }

    /**
     * Returns the identifier of the authorized resource owner.
     *
     * @return integer
     */
    public function getId()
    {
        return (int) $this->id;
    }

    /**
     * Returns the username of the authorized resource owner.
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Returns the title of the authorized resource owner.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Returns the email of the authorized resource owner.
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Returns the profile link of the authorized resource owner.
     *
     * @return string
     */
    public function getProfileUri()
    {
        return $this->link;
    }

    /**
     * Returns the avatar of the authorized resource owner.
     *
     * @return string
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * Return all of the owner details available as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'id'        => $this->getId(),
            'username'  => $this->getUsername(),
            'title'     => $this->getTitle(),
            'email'     => $this->getEmail(),
            'avatar'    => $this->getAvatar(),
            'profile'   => $this->getProfileUri(),
        ];
    }
}
