<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusBraintreePlugin\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\NotBlank;

final class BraintreeGatewayConfigurationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('merchantId', TextType::class, [
                'label' => 'bitbag_sylius_braintree_plugin.ui.merchant_id',
                'constraints' => [
                    new NotBlank([
                        'message' => 'bitbag_sylius_braintree_plugin.merchant_id.not_blank',
                        'groups' => 'sylius',
                    ]),
                ],
            ])
            ->add('publicKey', TextType::class, [
                'label' => 'bitbag_sylius_braintree_plugin.ui.public_key',
                'constraints' => [
                    new NotBlank([
                        'message' => 'bitbag_sylius_braintree_plugin.public_key.not_blank',
                        'groups' => 'sylius',
                    ]),
                ],
            ])
            ->add('privateKey', TextType::class, [
                'label' => 'bitbag_sylius_braintree_plugin.ui.private_key',
                'constraints' => [
                    new NotBlank([
                        'message' => 'bitbag_sylius_braintree_plugin.private_key.not_blank',
                        'groups' => 'sylius',
                    ]),
                ],
            ])
            ->add('sandbox', CheckboxType::class, [
                'label' => 'bitbag_sylius_braintree_plugin.ui.sandbox',
            ])
            ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
                $data = $event->getData();

                $data['payum.http_client'] = '@bitbag_sylius_braintree_plugin.api_client.braintree';

                $event->setData($data);
            })
        ;
    }
}
