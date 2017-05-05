<?php

namespace OC\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="OC\UserBundle\Repository\UserRepository")
 * @ORM\Table(name="user")
 * @UniqueEntity(fields="email", message="L'email existe déjà, merci d'en choisir un autre.")
 * @UniqueEntity(fields="username", message="Le nom d'utilisateur existe déjà, merci d'en choisir un autre.")
 */
class User implements UserInterface
{
	/**
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 * @ORM\Column(type="integer")
	 */
	private $id;
	/**
	 * @ORM\Column(type="string", unique=true)
	 * @Assert\Email(
	 *     message="L'email choisi n'est pas valide"
	 * )
	 */
	private $email;
	/**
	 * The encoded password
	 * @ORM\Column(type="string")
	 */
	private $password;

	/**
	 * @Assert\Length(
	 *     min = 6,
	 *     max = 15,
	 *     minMessage = "Votre mot de passe doit avoir un minimum de {{ limit }} charactères.",
	 *     maxMessage = "Votre mot de passe doit avoir un maximum de {{ limit }} charactères."
	 * )
	 */
	private $plainPassword;

	/**
	 * @ORM\Column(type="string", nullable=true)
	 */
	private $role;

	/**
	* @ORM\Column(type="string")
    */
	private $username;

	/**
	 * @ORM\Column(type="string", unique=true, nullable=true)
	 */
	private $apiToken;


	public function getUsername()
	{
		return $this->username;
	}

	public function getRole()
	{
		return $this->role;
	}

    public function getRoles() {
        return array($this->getRole());
    }

	public function getPassword()
	{
		return $this->password;
	}

	public function getSalt()
	{
		return null;
	}

	public function eraseCredentials()
	{
		$this->plainPassword = null;
	}

	public function getEmail()
	{
		return $this->email;
	}

	public function setEmail($email)
	{
		$this->email = $email;
	}

	public function setPassword($password)
	{
		$this->password = $password;
	}

	public function setPlainPassword($plainPassword)
	{
		$this->plainPassword = $plainPassword;
		$this->password = null;
	}

	public function getPlainPassword() {
		return $this->plainPassword;
	}

	/**
	 * @return mixed
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @return User
	 */
	public function setUsername( $username ) {
		$this->username = $username;
		return $this;
	}

	/**
	 * @param mixed $role
	 * @return User
	 */
	public function setRole( $role ) {
		$this->role = $role;
		return $this;
	}

	/**
	 * @return string
	 */
	public function __toString()
	{
		return $this->username;
	}

	/**
	 * @return mixed
	 */
	public function getApiToken() {
		return $this->apiToken;
	}

	/**
	 * @param mixed $apiToken
	 */
	public function setApiToken( $apiToken ) {
		$this->apiToken = $apiToken;
	}
}