declare module '@wordpress/data' {
	import type React from 'react';
	import type { WithoutInjectedProps } from '@wordpress/compose';
	import type Wp from '@konomi/wp-types';

	type DataRegistry = Readonly< {
		select: select;
		dispatch: dispatch;
	} >;

	type select = < S = Wp.Stores, SN extends keyof S = keyof S >(
		store: SN
		// TODO Double check Linting
		// @ts-expect-error
	) => S[ SN ][ 'select' ];

	type dispatch = < S = Wp.Stores, SN extends keyof S = keyof S >(
		store: SN
		// TODO Double check Linting
		// @ts-expect-error
	) => S[ SN ][ 'dispatch' ];

	type useSelect = < R = object >(
		fn: ( s: select ) => R,
		deps?: ReadonlyArray< unknown >
	) => R;

	type useDispatch = dispatch;

	type withSelect = < P = object, R = object >(
		fn: ( select: select, props: P, registry: DataRegistry ) => R
	) => < C >(
		// TODO Double check Linting
		// eslint-disable-next-line @typescript-eslint/prefer-readonly-parameter-types
		Component: React.FC< C >
	) => React.FC< WithoutInjectedProps< React.FC< C >, R > >;

	type withDispatch = < P = object, R = object >(
		fn: ( dispatch: dispatch, props: P, registry: DataRegistry ) => R
	) => < C >(
		// TODO Double check Linting
		// eslint-disable-next-line @typescript-eslint/prefer-readonly-parameter-types
		Component: React.FC< C >
	) => React.FC< WithoutInjectedProps< React.FC< C >, R > >;

	type subscribe = ( fn: () => void ) => void;

	export const select: select;
	export const dispatch: dispatch;
	export const withSelect: withSelect;
	export const withDispatch: withDispatch;
	export const useSelect: useSelect;
	export const useDispatch: useDispatch;
	// eslint-disable-next-line @typescript-eslint/ban-types
	export const combineReducers: Function;
	export const subscribe: subscribe;
	// eslint-disable-next-line @typescript-eslint/ban-types
	export const createReduxStore: Function;
	// eslint-disable-next-line @typescript-eslint/ban-types
	export const register: Function;
	export const AsyncModeProvider: React.FC< {
		value: unknown;
		children: React.ReactNode;
	} >;
}
