<?php

declare(strict_types=1);

it('renders title, text, the default icon and the action slot', function (): void {
    $html = render('<x-ui::empty-state title="No members yet" text="Invite someone."><a href="/invite">Invite</a></x-ui::empty-state>');

    expect($html)->toContain('No members yet')
        ->toContain('Invite someone.')
        ->toContain('href="/invite"')
        ->toContain('data-flux-icon');
});

it('omits the action wrapper when there is no slot', function (): void {
    expect(render('<x-ui::empty-state title="Nothing" />'))->not->toContain('mt-4');
});
