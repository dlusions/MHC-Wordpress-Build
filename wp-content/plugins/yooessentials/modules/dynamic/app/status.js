/* Essentials YOOtheme Pro 2.4.12 build 1202.1125; ZOOlanders https://www.zoolanders.com; Copyright (C) Joolanders, SL; http://www.gnu.org/licenses/gpl.html GNU/GPL */

import api from '@yootheme/api';
import hooks from '@yootheme/hooks';
import essentials from 'yooessentials';
import { flattenChildren } from '@yooessentials/util';
import Source from './Source';
import SourceHelper from './SourceHelper';
import { SourceIcon } from './components';

hooks.before('app.init', ({ extend }) => {
    extend({
        events: {
            statusIconsNode({ origin: Node, result = [] }) {
                // Check if the node has invalid sourced props
                if (hasSourcedProps(Node.node)) {
                    _iterateProps(getProps(Node.node), (prop) => {
                        let hasError = api.builder.helpers.Source.hasInvalidSource(
                            { source: prop },
                            essentials.yoo.Builder
                        );

                        if (hasError === 'empty-field') {
                            const source = new Source(prop, Node.node);
                            hasError = !source.fieldSchema;
                        } else if (hasError === 'empty-props') {
                            hasError = false;
                        }

                        if (hasError) {
                            result.push({
                                component: SourceIcon,
                                child: true,
                                error: true
                            });
                        }
                    });
                }

                return result;
            }
        }
    });
});

hooks.after('app.init', () => {
    const { Source } = api.builder.helpers;

    extendHasSource(Source);
    extendGetSourcePath(Source);
    extendHasInvalidSource(Source);
});

function extendGetSourcePath(Source) {
    // extend getSourcePath
    const getSourcePathFn = Source.getSourcePath.bind(Source);

    Source.getSourcePath = function (nodes, ...args) {
        const globalKey = SourceHelper.globalKey;

        let path = getSourcePathFn(nodes, ...args);

        if (path?.includes(globalKey)) {
            const id = getGobalQueryId(path);
            const globalQuery = essentials.helpers.Dynamic.getGlobalQuery(id);

            if (globalQuery) {
                path = path.replace(`${globalKey}${id}`, globalQuery?.source?.query?.name);
            }
        }

        return path;
    };
}

function extendHasSource(Source) {
    const hasSourceFn = Source.hasSource.bind(Source);

    Source.hasSource = function (node, ...args) {
        let result = hasSourceFn(node, ...args);
        return result || hasAssignedProps(node);
    };
}

function extendHasInvalidSource(Source) {
    const hasInvalidSourceFn = Source.hasInvalidSource.bind(Source);

    Source.hasInvalidSource = function (node, builder) {
        const source = this.getQuery(node);
        let result = hasInvalidSourceFn(node, builder);

        if (result === 'empty-field' && isGlobalQuery({ name: source })) {
            const id = getGobalQueryId(source);
            const globalQuery = essentials.helpers.Dynamic.getGlobalQuery(id);

            return !globalQuery;
        }

        // source with no props, could have deep nested props
        if (
            result === 'empty-props' &&
            (hasAssignedProps(node) || isInherited(node) || hasExtendingProps(node))
        ) {
            return false;
        }

        return result;
    };
}

const getProps = (node) => ({
    ...(node?.source?.props ?? {}),
    ...(node?.source_extended?.props ?? {})
});

const isSourced = (src) => src?.query;
const isExtending = (src) => src?.query?.field?.name;
const isNodeAssigned = (src) => src?.name && !src?.query;
const isNodeInheriting = (src) => src?.query?.name === SourceHelper.nodeKey;
const isParentInheriting = (src) => src?.query?.name === api.builder.helpers.Source.parentKey;

function getGobalQueryId(path) {
    return path.match(new RegExp(`${SourceHelper.globalKey}(\\w{6})`))?.[1] ?? '';
}

function isGlobalQuery(query) {
    return query?.name?.startsWith(SourceHelper.globalKey);
}

function hasSourcedProps(node) {
    return _iterateProps(getProps(node), (prop) => isSourced(prop));
}

function hasAssignedProps(node) {
    return _iterateProps(getProps(node), (prop) => isNodeAssigned(prop) || isNodeInheriting(prop));
}

function hasExtendingProps(node) {
    return _iterateProps(getProps(node), isExtending);
}

function isInherited(node) {
    const { Source } = api.builder.helpers;
    const children = flattenChildren(node);

    return children.some((child, i) => {
        const props = getProps(child);
        const parent = children[i - 1];
        const parentQuery = Source.getQuery(parent || {});

        // if nested nodes have query the
        // main node is not the one being inherited
        if (parentQuery) {
            return false;
        }

        let result;

        if (child.source?.query?.arguments_extended) {
            result = _iterateProps(child.source.query.arguments_extended, isNodeInheriting);
            result = _iterateProps(child.source.query.arguments_extended, isParentInheriting);
        }

        return (
            result ||
            _iterateProps(props, isNodeInheriting) ||
            _iterateProps(props, isParentInheriting)
        );
    });
}

function _iterateProps(props, cb) {
    return Object.values(props).some((prop) => {
        if (prop?.composed) {
            const inSources = _iterateProps(prop.composed.sources || {}, cb);
            const inConditions = Object.values(prop.composed.conditions || {}).some((prop) => {
                return prop.rules?.some((rule) => _iterateProps(getProps(rule), cb));
            });

            return inSources || inConditions;
        }

        if (prop?.query?.arguments_extended) {
            return _iterateProps(prop.query.arguments_extended, cb);
        }

        if (prop?.query?.arguments?.filters) {
            return prop.query.arguments.filters.some((condition) =>
                _iterateProps(getProps(condition), cb)
            );
        }

        if (prop?.query?.arguments?.ordering) {
            return prop.query.arguments.filters.some((condition) =>
                _iterateProps(getProps(condition), cb)
            );
        }

        return cb(prop);
    });
}
