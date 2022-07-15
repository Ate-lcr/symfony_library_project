<?php

namespace App\Form;

use App\Entity\Book;
use App\Entity\Author;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class BookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Title')
            ->add('nbPages')
            ->add('publishedAt', DateType::class, ['widget' => 'single_text',])
            ->add('coverImg', FileType::class,[
                'label' => 'Image (Jpeg file)',
                'mapped' => false,
                'required' => false,
            ])
            ->add('author', EntityType::class,['class' => Author::class,'choice_label' => 'last_name',])
            ->add('submit', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
        ]);
    }
}
