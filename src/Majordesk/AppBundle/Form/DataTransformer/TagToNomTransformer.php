<?php
namespace Majordesk\AppBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Doctrine\Common\Persistence\ObjectManager;
use Majordesk\AppBundle\Entity\Tag;

class TagToNomTransformer implements DataTransformerInterface
{
    /**
     * @var ObjectManager
     */
    private $om;

    /**
     * @param ObjectManager $om
     */
    public function __construct(ObjectManager $om)
    {
        $this->om = $om;
    }

    /**
     * Transforms an object (tag) to a string (nom).
     *
     * @param  Tag|null $tag
     * @return string
     */
    public function transform($tags)
    {
		$noms = array();
	
        if (empty($tags)) {
            return $noms;
        }
		
		foreach($tags as $tag) {
			$noms[] = $tag->getNom();
		}

        return $noms;
    }

    /**
     * Transforms a string (nom) to an object (tag).
     *
     * @param  string $nom
     * @return Tag|null
     * @throws TransformationFailedException if object (tag) is not found.
     */
    public function reverseTransform($noms)
    {
		$tags = new \Doctrine\Common\Collections\ArrayCollection();
	
        if (empty($noms)) {
            return $tags;
        }
		
		foreach($noms as $nom) {
			$tag = $this->om
						->getRepository('MajordeskAppBundle:Tag')
						->findOneBy(array('nom' => $nom));
			
			if (null === $tag) {
				$tag = new Tag();
				$tag->setNom($nom);
			}			
						
			$tags[] = $tag;
		}

        return $tags;
    }
}