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

class TicketType extends AbstractType
{
    /**
     * @var SlotRepository
     */
    private $slotRepository;

    public function __construct(SlotRepository $slotRepository)
    {

        $this->slotRepository = $slotRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $movieDate = $options["movieDate"];
        $slots = $this->slotRepository->findFreeSlots($movieDate);

        $formatted = [];

        foreach ($slots as $slot) {
            $formatted += [$slot->getChair() => $slot];
        }

        $builder
            ->add('Slot', ChoiceType::class, [
                'choices' => $formatted
            ])
            ->add('FirstName', TextType::class)
            ->add("LastName", TextType::class)
            ->add('Email', TextType::class)
            ->add('Phone', NumberType::class)
            ->add('Buy', SubmitType::class);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'movieDate' => null,
        ]);
    }
}