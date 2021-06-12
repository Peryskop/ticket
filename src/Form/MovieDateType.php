<?php


namespace App\Form;


use App\Entity\Movie;
use App\Repository\MovieRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MovieDateType extends AbstractType
{

    /**
     * @var MovieRepository
     */
    private $movieRepository;

    public function __construct(MovieRepository $movieRepository)
    {
        $this->movieRepository = $movieRepository;
    }



    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $movies = $this->movieRepository->findBy(['cinema' => $options['cinema']]);
        $formatted = [];

        foreach ($movies as $movie) {
            $formatted += [$movie->getTitle() => $movie];
        }

        $builder
            ->add('Movie', ChoiceType::class, [
                'choices' => $formatted
            ])
            ->add('Date', DateTimeType::class)
            ->add('Save', SubmitType::class);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'cinema' => null,
        ]);
    }
}