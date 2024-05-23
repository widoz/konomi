import React from 'react'

import {useSelect} from '@wordpress/data'
import {createHigherOrderComponent} from '@wordpress/compose'

export const withConfiguration = createHigherOrderComponent(
  Component => props => {
	  const konomi = useSelect(select => select('core').getSite().konomi, [])

	  return <Component {...props} configuration={konomi} />
  },
  'withConfiguration',
)
