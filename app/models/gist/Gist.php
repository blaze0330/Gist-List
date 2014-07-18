<?php

class Gist
{
    /** @type int $id */
    private $id;

    /** @type array $owner */
    private $owner = [];

    /** @type string $description */
    private $description;

    /** @type array $tags */
    private $tags = [];

    /** @type string $createdAt */
    private $createdAt;

    /** @type string $updatedAt */
    private $updatedAt;

    /** @type array $files */
    private $files = [];

    /** @type boolean $isPublic */
    private $isPublic;

    /** @type boolean $isStarred */
    private $isStarred;

    /** @type string $htmlUrl */
    private $htmlUrl;

    /**
     * Get a list of counted tags
     *
     * Counts the number of each tag occurences, of Gists with no tag, of public, private, user own, starred
     * and all Gists together.
     *
     * @param Gist[] $gists
     * @param string $ownerId  GitHub id of owner of Gists
     *
     * @return array  Returns an array where the key is a tag or a kind of Gist and the value a number of occurences
     */
    public static function getListOfCountedTags(array $gists, $ownerId)
    {
        $tagsList = [
            'public'  => 0,
            'private' => 0,
            'noTag'   => 0,
            'all'     => 0,
            'myGists' => 0,
            'starred' => 0
        ];
        foreach ($gists as $gist) {
            foreach ($gist->getTags() as $tagFromGist) {
                $keyOfTagAlreadyInList = false;
                foreach ($tagsList as $listKey => $tagFromList) {
                    if ($tagFromGist === $tagFromList['name']) {
                        $keyOfTagAlreadyInList = $listKey;
                    }
                }
                if ($keyOfTagAlreadyInList === false) {
                    $tagsList[] = ['name' => $tagFromGist, 'count' => 1];

                }
                else {
                    $tagsList[$keyOfTagAlreadyInList]['count'] += 1;
                }
            }

            if ($gist->getIsPublic()) {
                $tagsList['public'] += 1;
            }
            else {
                $tagsList['private'] += 1;
            }

            if (!$gist->getTags()) {
                $tagsList['noTag'] += 1;
            }

            if ($gist->getOwner()['id'] === $ownerId) {
                $tagsList['myGists'] += 1;
            }

            if ($gist->getIsStarred() === true) {
                $tagsList['starred'] += 1;
            }

            $tagsList['all'] += 1;
        }

        return $tagsList;
    }

    /**
     * Convert date to 'Y-m-d H:i:s' format
     *
     * @param $date
     *
     * @return bool | string
     */
    private function convertDate($date)
    {
        $convertedDate = date('Y-m-d H:i:s', strtotime($date));

        return $convertedDate;
    }

    // Accessors and mutators

    /**
     * Set Gist description and tags
     *
     * Seeks tags in original Gist description and saves them and description separately
     *
     * @param $description  Gist description with optional tags
     */
    public function setDescriptionAndTags($description)
    {
        $tags = [];
        $descriptionWithoutTags = trim(
            preg_replace_callback(
                '~#\w+~',
                function($matches) use (&$tags) {
                    $tags[] = $matches[0];
                },
                $description
            )
        );

        $this->setDescription($descriptionWithoutTags);
        $this->setTags($tags);
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param array $owner
     */
    public function setOwner($owner)
    {
        $this->owner = $owner;
    }

    /**
     * @return array
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * @param string $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $this->convertDate($createdAt);
    }

    /**
     * @return string
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param string $description
     */
    private function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param array $files
     */
    public function setFiles($files)
    {
        $this->files = $files;
    }

    /**
     * @return array
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * @param array $tags
     */
    private function setTags($tags)
    {
        $this->tags = $tags;
    }

    /**
     * @return array
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @param string $updatedAt
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $this->convertDate($updatedAt);
    }

    /**
     * @return string
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param boolean $isPublic
     */
    public function setIsPublic($isPublic)
    {
        $this->isPublic = $isPublic;
    }

    /**
     * @return boolean
     */
    public function getIsPublic()
    {
        return $this->isPublic;
    }

    /**
     * @param string $htmlUrl
     */
    public function setHtmlUrl($htmlUrl)
    {
        $this->htmlUrl = $htmlUrl;
    }

    /**
     * @param boolean $isStarred
     */
    public function setIsStarred($isStarred)
    {
        $this->isStarred = $isStarred;
    }

    /**
     * @return boolean
     */
    public function getIsStarred()
    {
        return $this->isStarred;
    }

    /**
     * @return string
     */
    public function getHtmlUrl()
    {
        return $this->htmlUrl;
    }
}