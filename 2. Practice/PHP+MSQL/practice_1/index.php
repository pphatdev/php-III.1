<?php
    // Get users from database
    require_once 'src/schema/users/read.php';
    $users = getUsers();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employees</title>
    <link rel="stylesheet" href="./dist/styles.css">
    <script src="./src/js/pages/index.js" defer type="module"></script>
</head>
<body>

    <main class="mt-5 w-full max-w-7xl mx-auto">
        <div class="px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 ">
                <div>
                    <h1 class="text-lg font-bold">Employees</h1>
                    <p class="mt-2 text-sm text-foreground/70">A list of all the employees in your account including their name, title, email and role.</p>
                </div>
                <div class="ml-auto flex items-center justify-end gap-2">
                    <button type="button" data-action="create-user" class="button bg-primary text-primary-foreground focus-within:border-primary focus-within:ring-4 focus-within:ring-primary/20">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-5"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" /><path d="M16 19h6" /><path d="M19 16v6" /><path d="M6 21v-2a4 4 0 0 1 4 -4h4" /></svg>
                        New
                    </button>
                </div>
            </div>
            <div class="mt-8 flow-root border border-foreground/10 overflow-x-auto rounded-md">
                <table class="min-w-full border-separate border-spacing-0">
                    <thead class="bg-foreground/10">
                        <tr class="*:py-2 *:text-left *:font-semibold *:px-3 *:border-b *:border-foreground/10">
                            <th scope="col">Name</th>
                            <th scope="col">Position</th>
                            <th scope="col">Email</th>
                            <th scope="col">Role</th>
                            <th scope="col">
                                <span class="sr-only">Actions</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="text-foreground/70">

                        <?php
                            foreach ($users as $user):
                            // Convert to object
                            $user = (object)$user;
                        ?>
                            <tr class="*:py-1.5 *:px-3 not-first:*:border-t *:border-foreground/10">
                                <td class="whitespace-nowrap"><?php echo($user->name ?? ''); ?></td>
                                <td class="whitespace-nowrap"><?php echo($user->title ?? ''); ?></td>
                                <td><?php echo($user->email ?? ''); ?></td>
                                <td><?php echo($user->role ?? ''); ?></td>
                                <td class="w-fit text-right">
                                    <div class="inline-flex py-1 mx-auto shrink-0 w-fit gap-1 items-center">
                                        <a href="javascript:void(0);"
                                            data-action="edit-user"
                                            data-id="<?php echo($user->id ?? ''); ?>"
                                            data-position="<?php echo(htmlspecialchars($user->title ?? '')); ?>"
                                            data-name="<?php echo(htmlspecialchars($user->name ?? '')); ?>"
                                            data-email="<?php echo(htmlspecialchars($user->email ?? '')); ?>"
                                            data-role="<?php echo(htmlspecialchars($user->role ?? '')); ?>"
                                            class="button px-[0.26rem] text-base focus-within:border-primary focus-within:ring-4 focus-within:ring-primary/20"
                                        >
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-4">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
                                                <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />
                                                <path d="M16 5l3 3" />
                                            </svg>
                                        </a>
                                        <a
                                            href="javascript:void(0);"
                                            data-action="view-user"
                                            data-id="<?php echo($user->id ?? ''); ?>"
                                            data-position="<?php echo(htmlspecialchars($user->title ?? '')); ?>"
                                            data-name="<?php echo(htmlspecialchars($user->name ?? '')); ?>"
                                            data-email="<?php echo(htmlspecialchars($user->email ?? '')); ?>"
                                            data-role="<?php echo(htmlspecialchars($user->role ?? '')); ?>"
                                            class="button px-[0.26rem] text-base focus-within:border-primary focus-within:ring-4 focus-within:ring-primary/20"
                                        >
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-4">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                                <path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" />
                                            </svg>
                                        </a>
                                        <a
                                            href="javascript:void(0);"
                                            data-action="delete-user"
                                            data-id="<?php echo($user->id ?? ''); ?>"
                                            class="button px-1 bg-destructive text-destructive-foreground focus-within:border-destructive focus-within:ring-4 focus-within:ring-destructive/20"
                                        >
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-4">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                <path d="M4 7l16 0" />
                                                <path d="M10 11l0 6" />
                                                <path d="M14 11l0 6" />
                                                <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                                                <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                                            </svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php
                        endforeach;
                            if (count($users) == 0):
                        ?>
                            <tr class="*:py-2 *:px-3 not-first:*:border-t *:border-foreground/10">
                                <td colspan="5" class="text-center py-5 text-foreground/50">No users found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

</body>
</html>