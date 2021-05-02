<?php


namespace App\Form;


use App\Entity\MovieDate;
use App\Entity\Slot;
use App\Repository\SlotRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;

class EmployeeTicketType extends AbstractType
{
    /**
     * @var SlotRepository
     */
    private $slotRepository;
    /**
     * @var Security
     */
    private $security;

    public function __construct(SlotRepository $slotRepository, Security $security)
    {

        $this->slotRepository = $slotRepository;
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $movieDate = $options["movieDate"];
        $slots = $this->slotRepository->findFreeSlots($movieDate);
        $user = $this->security->getUser();

        $formatted = [];

        foreach ($slots as $slot) {
            $formatted += [$slot->getChair() => $slot];
        }

        $builder
            ->add('Slot', ChoiceType::class, [
                'choices' => $formatted
            ])
            ->add('FirstName', HiddenType::class, ['data' => "em"])
            ->add("LastName", HiddenType::class, ['data' => "em"])
            ->add('Email', HiddenType::class, ['data' => $user->getUsername()])
            ->add('Phone', HiddenType::class, ['data' => "em"])
            ->add('Buy', SubmitType::class);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'movieDate' => null,
        ]);
    }
}